# Firebase Integration Guide - Matrimonial API

## 🔥 Firebase Services Overview

Firebase provides multiple services that can enhance your matrimonial API project:

### **Primary Services:**

-   **Firebase Storage** - File storage for profile pictures and media
-   **Firebase Authentication** - User authentication (alternative to Laravel Sanctum)
-   **Firebase Cloud Messaging (FCM)** - Push notifications
-   **Firebase Realtime Database** - Real-time data synchronization
-   **Firebase Cloud Functions** - Serverless backend functions

## 📁 Firebase Storage Integration

### **1. Setup Firebase Storage**

#### **Install Firebase SDK**

```bash
composer require kreait/laravel-firebase
```

#### **Configure Firebase**

```php
// config/firebase.php
return [
    'credentials' => [
        'file' => storage_path('firebase-credentials.json'),
    ],
    'project_id' => env('FIREBASE_PROJECT_ID'),
    'storage_bucket' => env('FIREBASE_STORAGE_BUCKET'),
];
```

#### **Environment Variables**

```env
FIREBASE_PROJECT_ID=your-project-id
FIREBASE_STORAGE_BUCKET=your-project-id.appspot.com
FIREBASE_CREDENTIALS_PATH=storage/firebase-credentials.json
```

### **2. Firebase Storage Service**

```php
<?php
// app/Services/FirebaseStorageService.php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Storage;

class FirebaseStorageService
{
    private $storage;
    private $bucket;

    public function __construct()
    {
        $this->storage = (new Factory)
            ->withServiceAccount(storage_path('firebase-credentials.json'))
            ->createStorage();

        $this->bucket = $this->storage->getBucket();
    }

    /**
     * Upload profile picture
     */
    public function uploadProfilePicture($file, $userId)
    {
        $fileName = "profile_pictures/{$userId}/" . time() . '_' . $file->getClientOriginalName();

        $object = $this->bucket->upload(
            fopen($file->getPathname(), 'r'),
            [
                'name' => $fileName,
                'metadata' => [
                    'contentType' => $file->getMimeType(),
                ]
            ]
        );

        return $object->info()['mediaLink'];
    }

    /**
     * Upload message media
     */
    public function uploadMessageMedia($file, $conversationId)
    {
        $fileName = "messages/{$conversationId}/" . time() . '_' . $file->getClientOriginalName();

        $object = $this->bucket->upload(
            fopen($file->getPathname(), 'r'),
            [
                'name' => $fileName,
                'metadata' => [
                    'contentType' => $file->getMimeType(),
                ]
            ]
        );

        return $object->info()['mediaLink'];
    }

    /**
     * Delete file
     */
    public function deleteFile($fileUrl)
    {
        $path = parse_url($fileUrl, PHP_URL_PATH);
        $path = str_replace('/storage/v1/b/' . env('FIREBASE_STORAGE_BUCKET') . '/o/', '', $path);
        $path = urldecode($path);

        $object = $this->bucket->object($path);
        $object->delete();
    }

    /**
     * Get signed URL for private files
     */
    public function getSignedUrl($filePath, $expires = '+1 hour')
    {
        $object = $this->bucket->object($filePath);
        return $object->signedUrl(new \DateTime($expires));
    }
}
```

### **3. Updated User Controller with Firebase**

```php
<?php
// app/Http/Controllers/Api/UserController.php

use App\Services\FirebaseStorageService;

class UserController extends Controller
{
    private $firebaseStorage;

    public function __construct(FirebaseStorageService $firebaseStorage)
    {
        $this->firebaseStorage = $firebaseStorage;
    }

    /**
     * Upload profile picture
     */
    public function uploadProfilePicture(Request $request)
    {
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $user = auth()->user();
        $file = $request->file('profile_picture');

        try {
            // Upload to Firebase Storage
            $fileUrl = $this->firebaseStorage->uploadProfilePicture($file, $user->id);

            // Update user profile
            $user->update(['profile_picture' => $fileUrl]);

            return response()->json([
                'message' => 'Profile picture uploaded successfully',
                'profile_picture' => $fileUrl
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to upload profile picture',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'age' => 'sometimes|integer|min:18|max:100',
            'bio' => 'sometimes|string|max:1000',
            // ... other validation rules
        ]);

        $user = auth()->user();
        $user->update($request->validated());

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user->fresh()
        ], 200);
    }
}
```

### **4. Message Controller with Media Support**

```php
<?php
// app/Http/Controllers/Api/MessageController.php

class MessageController extends Controller
{
    private $firebaseStorage;

    public function __construct(FirebaseStorageService $firebaseStorage)
    {
        $this->firebaseStorage = $firebaseStorage;
    }

    /**
     * Send message with media
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'to_user_id' => 'required|exists:users,id',
            'message' => 'required_without:media|string|max:1000',
            'media' => 'sometimes|file|mimes:jpeg,png,jpg,gif,mp4|max:10240'
        ]);

        $user = auth()->user();
        $toUserId = $request->to_user_id;

        // Verify match exists
        $match = Match::where(function($query) use ($user, $toUserId) {
            $query->where('user_id', $user->id)
                  ->where('matched_user_id', $toUserId);
        })->orWhere(function($query) use ($user, $toUserId) {
            $query->where('user_id', $toUserId)
                  ->where('matched_user_id', $user->id);
        })->first();

        if (!$match) {
            return response()->json(['message' => 'You can only message matched users'], 403);
        }

        $messageData = [
            'from_user_id' => $user->id,
            'to_user_id' => $toUserId,
            'message' => $request->message,
            'media_url' => null,
            'media_type' => null
        ];

        // Handle media upload
        if ($request->hasFile('media')) {
            $file = $request->file('media');
            $conversationId = $this->getConversationId($user->id, $toUserId);

            try {
                $mediaUrl = $this->firebaseStorage->uploadMessageMedia($file, $conversationId);
                $messageData['media_url'] = $mediaUrl;
                $messageData['media_type'] = $file->getMimeType();
            } catch (\Exception $e) {
                return response()->json([
                    'message' => 'Failed to upload media',
                    'error' => $e->getMessage()
                ], 500);
            }
        }

        $message = Message::create($messageData);

        return response()->json([
            'message' => 'Message sent successfully',
            'chat_message' => $message
        ], 201);
    }

    private function getConversationId($user1Id, $user2Id)
    {
        return min($user1Id, $user2Id) . '_' . max($user1Id, $user2Id);
    }
}
```

## 🔔 Firebase Cloud Messaging (FCM) Integration

### **1. FCM Service**

```php
<?php
// app/Services/FCMService.php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class FCMService
{
    private $serverKey;
    private $fcmUrl = 'https://fcm.googleapis.com/fcm/send';

    public function __construct()
    {
        $this->serverKey = env('FIREBASE_SERVER_KEY');
    }

    /**
     * Send push notification
     */
    public function sendNotification($token, $title, $body, $data = [])
    {
        $payload = [
            'to' => $token,
            'notification' => [
                'title' => $title,
                'body' => $body,
                'sound' => 'default',
                'badge' => 1
            ],
            'data' => $data
        ];

        $response = Http::withHeaders([
            'Authorization' => 'key=' . $this->serverKey,
            'Content-Type' => 'application/json'
        ])->post($this->fcmUrl, $payload);

        return $response->json();
    }

    /**
     * Send notification to multiple users
     */
    public function sendNotificationToMultiple($tokens, $title, $body, $data = [])
    {
        $payload = [
            'registration_ids' => $tokens,
            'notification' => [
                'title' => $title,
                'body' => $body,
                'sound' => 'default',
                'badge' => 1
            ],
            'data' => $data
        ];

        $response = Http::withHeaders([
            'Authorization' => 'key=' . $this->serverKey,
            'Content-Type' => 'application/json'
        ])->post($this->fcmUrl, $payload);

        return $response->json();
    }
}
```

### **2. Updated Notification Service**

```php
<?php
// app/Services/NotificationService.php

use App\Services\FCMService;

class NotificationService
{
    private $fcmService;

    public function __construct(FCMService $fcmService)
    {
        $this->fcmService = $fcmService;
    }

    /**
     * Send match notification
     */
    public function sendMatchNotification($user1, $user2)
    {
        // Send to user 1
        if ($user1->fcm_token) {
            $this->fcmService->sendNotification(
                $user1->fcm_token,
                'New Match! 💕',
                "You and {$user2->name} liked each other!",
                [
                    'type' => 'match',
                    'user_id' => $user2->id,
                    'user_name' => $user2->name
                ]
            );
        }

        // Send to user 2
        if ($user2->fcm_token) {
            $this->fcmService->sendNotification(
                $user2->fcm_token,
                'New Match! 💕',
                "You and {$user1->name} liked each other!",
                [
                    'type' => 'match',
                    'user_id' => $user1->id,
                    'user_name' => $user1->name
                ]
            );
        }
    }

    /**
     * Send message notification
     */
    public function sendMessageNotification($sender, $receiver, $message)
    {
        if ($receiver->fcm_token) {
            $this->fcmService->sendNotification(
                $receiver->fcm_token,
                "New message from {$sender->name}",
                $message,
                [
                    'type' => 'message',
                    'sender_id' => $sender->id,
                    'sender_name' => $sender->name,
                    'message' => $message
                ]
            );
        }
    }
}
```

## 🔐 Firebase Authentication (Optional)

### **Firebase Auth Integration**

```php
<?php
// app/Services/FirebaseAuthService.php

namespace App\Services;

use Kreait\Firebase\Auth;

class FirebaseAuthService
{
    private $auth;

    public function __construct()
    {
        $this->auth = (new Factory)
            ->withServiceAccount(storage_path('firebase-credentials.json'))
            ->createAuth();
    }

    /**
     * Verify Firebase ID token
     */
    public function verifyIdToken($idToken)
    {
        try {
            $verifiedIdToken = $this->auth->verifyIdToken($idToken);
            return $verifiedIdToken->claims()->get('sub'); // User UID
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Create custom token
     */
    public function createCustomToken($uid, $claims = [])
    {
        return $this->auth->createCustomToken($uid, $claims);
    }
}
```

## 📱 Mobile App Integration

### **Flutter Example**

```dart
// Firebase Storage Upload
Future<String> uploadProfilePicture(File imageFile) async {
  final storageRef = FirebaseStorage.instance
      .ref()
      .child('profile_pictures/${user.uid}/${DateTime.now().millisecondsSinceEpoch}.jpg');

  final uploadTask = storageRef.putFile(imageFile);
  final snapshot = await uploadTask;
  final downloadUrl = await snapshot.ref.getDownloadURL();

  return downloadUrl;
}

// FCM Token Management
Future<void> setupFCM() async {
  final fcmToken = await FirebaseMessaging.instance.getToken();

  // Send token to your Laravel API
  await http.post(
    Uri.parse('${apiUrl}/api/fcm-token'),
    headers: {'Authorization': 'Bearer $authToken'},
    body: {'fcm_token': fcmToken}
  );
}
```

## 🚀 Benefits of Firebase Integration

### **1. Scalability**

-   Automatic scaling based on usage
-   Global CDN for fast file delivery
-   No server maintenance required

### **2. Security**

-   Built-in authentication and authorization
-   Secure file access with signed URLs
-   Automatic virus scanning

### **3. Cost-Effective**

-   Pay only for storage and bandwidth used
-   No upfront infrastructure costs
-   Automatic optimization

### **4. Developer Experience**

-   Easy integration with mobile apps
-   Real-time updates
-   Comprehensive SDKs

## 📊 Firebase vs Other Options

| Feature         | Firebase Storage | AWS S3      | Local Storage |
| --------------- | ---------------- | ----------- | ------------- |
| **Setup**       | Easy             | Complex     | Simple        |
| **Scalability** | Automatic        | Manual      | Limited       |
| **CDN**         | Built-in         | CloudFront  | None          |
| **Cost**        | Pay-per-use      | Pay-per-use | Fixed         |
| **Mobile SDK**  | Excellent        | Good        | None          |
| **Real-time**   | Yes              | No          | No            |

## 🎯 Implementation Steps

1. **Setup Firebase Project**

    - Create Firebase project
    - Download credentials JSON
    - Configure environment variables

2. **Install Dependencies**

    - Laravel Firebase package
    - Mobile SDKs

3. **Update Controllers**

    - Integrate Firebase Storage service
    - Add media upload endpoints

4. **Configure Security Rules**

    - Set up Firebase Storage rules
    - Configure authentication

5. **Test Integration**
    - Test file uploads
    - Verify push notifications
    - Check mobile app integration

---

**Firebase is an excellent choice for your matrimonial API project, providing scalable file storage, real-time notifications, and easy mobile integration.**
