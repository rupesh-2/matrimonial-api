# 📸 Profile Image Upload API - Matrimonial Application

## 📋 **Overview**

This document outlines the fixed profile image upload functionality for the matrimonial application. The API now properly handles file uploads with validation, storage, and URL generation.

## 🚀 **Features Implemented**

### **Image Upload Capabilities**
- **File Validation**: Supports JPEG, PNG, JPG, GIF formats
- **Size Limit**: Maximum 2MB per image
- **Automatic Cleanup**: Old images are deleted when new ones are uploaded
- **URL Generation**: Automatic public URL generation for uploaded images
- **Fallback Avatars**: Default avatar generation when no image is uploaded

## 🔗 **API Endpoints**

### **1. Update Profile (with Image Upload)**
```http
PUT /api/profile
```

**Request (Multipart Form Data):**
```
Content-Type: multipart/form-data

name: John Doe
age: 25
gender: male
religion: Hindu
bio: Looking for a life partner
profile_picture: [file upload]
```

**Response:**
```json
{
    "message": "Profile updated successfully",
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "age": 25,
        "gender": "male",
        "religion": "Hindu",
        "bio": "Looking for a life partner",
        "profile_picture": "profile_pictures/1735489200_abc123def.jpg",
        "profile_picture_url": "http://localhost:8000/storage/profile_pictures/1735489200_abc123def.jpg",
        "profile_picture_url_with_fallback": "http://localhost:8000/storage/profile_pictures/1735489200_abc123def.jpg"
    }
}
```

### **2. Upload Profile Picture Only**
```http
POST /api/profile/picture
```

**Request (Multipart Form Data):**
```
Content-Type: multipart/form-data

profile_picture: [file upload]
```

**Response:**
```json
{
    "message": "Profile picture uploaded successfully",
    "profile_picture": "profile_pictures/1735489200_abc123def.jpg",
    "profile_picture_url": "http://localhost:8000/storage/profile_pictures/1735489200_abc123def.jpg"
}
```

### **3. Delete Profile Picture**
```http
DELETE /api/profile/picture
```

**Response:**
```json
{
    "message": "Profile picture deleted successfully"
}
```

### **4. Get Profile Picture URL**
```http
GET /api/profile/picture
```

**Response:**
```json
{
    "profile_picture_url": "http://localhost:8000/storage/profile_pictures/1735489200_abc123def.jpg"
}
```

**Response (No Image):**
```json
{
    "message": "No profile picture found",
    "profile_picture_url": null
}
```

## 📱 **Frontend Integration Examples**

### **React Native/Expo Example**

#### **Upload Profile Picture**
```javascript
import * as ImagePicker from 'expo-image-picker';

const uploadProfilePicture = async () => {
  try {
    // Request permissions
    const { status } = await ImagePicker.requestMediaLibraryPermissionsAsync();
    if (status !== 'granted') {
      alert('Sorry, we need camera roll permissions to make this work!');
      return;
    }

    // Pick image
    const result = await ImagePicker.launchImageLibraryAsync({
      mediaTypes: ImagePicker.MediaTypeOptions.Images,
      allowsEditing: true,
      aspect: [1, 1],
      quality: 0.8,
    });

    if (!result.canceled) {
      // Create form data
      const formData = new FormData();
      formData.append('profile_picture', {
        uri: result.assets[0].uri,
        type: 'image/jpeg',
        name: 'profile_picture.jpg',
      });

      // Upload to API
      const token = await AsyncStorage.getItem('auth_token');
      const response = await fetch('/api/profile/picture', {
        method: 'POST',
        headers: {
          'Authorization': `Bearer ${token}`,
          'Content-Type': 'multipart/form-data',
        },
        body: formData,
      });

      const data = await response.json();
      console.log('Upload successful:', data);
    }
  } catch (error) {
    console.error('Upload error:', error);
  }
};
```

#### **Update Profile with Image**
```javascript
const updateProfileWithImage = async (profileData, imageUri = null) => {
  try {
    const formData = new FormData();
    
    // Add text fields
    Object.keys(profileData).forEach(key => {
      formData.append(key, profileData[key]);
    });

    // Add image if provided
    if (imageUri) {
      formData.append('profile_picture', {
        uri: imageUri,
        type: 'image/jpeg',
        name: 'profile_picture.jpg',
      });
    }

    const token = await AsyncStorage.getItem('auth_token');
    const response = await fetch('/api/profile', {
      method: 'PUT',
      headers: {
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'multipart/form-data',
      },
      body: formData,
    });

    const data = await response.json();
    return data;
  } catch (error) {
    console.error('Update profile error:', error);
    throw error;
  }
};
```

#### **Display Profile Picture with Fallback**
```javascript
import { Image } from 'react-native';

const ProfilePicture = ({ user }) => {
  const imageUrl = user.profile_picture_url_with_fallback || user.profile_picture_url;

  return (
    <Image
      source={{ uri: imageUrl }}
      style={{ width: 100, height: 100, borderRadius: 50 }}
      defaultSource={require('./assets/default-avatar.png')}
    />
  );
};
```

### **Web/JavaScript Example**

#### **Upload Profile Picture**
```javascript
const uploadProfilePicture = async (file) => {
  try {
    const formData = new FormData();
    formData.append('profile_picture', file);

    const token = localStorage.getItem('auth_token');
    const response = await fetch('/api/profile/picture', {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${token}`,
      },
      body: formData,
    });

    const data = await response.json();
    return data;
  } catch (error) {
    console.error('Upload error:', error);
    throw error;
  }
};

// Usage with file input
document.getElementById('profile-picture-input').addEventListener('change', async (event) => {
  const file = event.target.files[0];
  if (file) {
    try {
      const result = await uploadProfilePicture(file);
      console.log('Upload successful:', result);
    } catch (error) {
      console.error('Upload failed:', error);
    }
  }
});
```

## 🧪 **Testing Examples**

### **cURL Commands**

#### **Upload Profile Picture**
```bash
curl -X POST http://localhost:8000/api/profile/picture \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -F "profile_picture=@/path/to/your/image.jpg"
```

#### **Update Profile with Image**
```bash
curl -X PUT http://localhost:8000/api/profile \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -F "name=John Doe" \
  -F "age=25" \
  -F "gender=male" \
  -F "profile_picture=@/path/to/your/image.jpg"
```

#### **Delete Profile Picture**
```bash
curl -X DELETE http://localhost:8000/api/profile/picture \
  -H "Authorization: Bearer YOUR_TOKEN"
```

#### **Get Profile Picture URL**
```bash
curl -X GET http://localhost:8000/api/profile/picture \
  -H "Authorization: Bearer YOUR_TOKEN"
```

## 🔒 **Validation Rules**

### **Image Validation**
- **File Type**: Only JPEG, PNG, JPG, GIF allowed
- **File Size**: Maximum 2MB
- **Required**: Profile picture is optional

### **Error Responses**

#### **Invalid File Type**
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "profile_picture": ["The profile picture must be an image."]
    }
}
```

#### **File Too Large**
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "profile_picture": ["The profile picture may not be greater than 2048 kilobytes."]
    }
}
```

#### **No File Provided**
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "profile_picture": ["The profile picture field is required."]
    }
}
```

## 🗄️ **Storage Configuration**

### **File Storage Structure**
```
storage/
├── app/
│   └── public/
│       └── profile_pictures/
│           ├── 1735489200_abc123def.jpg
│           ├── 1735489300_xyz789ghi.png
│           └── ...
└── ...
```

### **Public Access**
Files are accessible via: `http://your-domain.com/storage/profile_pictures/filename.jpg`

## 🔄 **Business Logic**

### **Upload Flow**
1. **Validation**: Check file type, size, and format
2. **Cleanup**: Delete old profile picture if exists
3. **Storage**: Save new image with unique filename
4. **Database**: Update user record with file path
5. **Response**: Return success message and URLs

### **Filename Generation**
- **Format**: `profile_pictures/timestamp_randomstring.extension`
- **Example**: `profile_pictures/1735489200_abc123def.jpg`
- **Uniqueness**: Timestamp + random string ensures uniqueness

### **Automatic Cleanup**
- Old images are automatically deleted when new ones are uploaded
- Prevents storage bloat and orphaned files

## 📊 **User Model Attributes**

### **New Accessors**
```php
// Get profile picture URL
$user->profile_picture_url

// Get profile picture URL with fallback avatar
$user->profile_picture_url_with_fallback
```

### **Example Usage**
```php
// In controller or service
$user = User::find(1);
echo $user->profile_picture_url; // Full URL to image
echo $user->profile_picture_url_with_fallback; // URL or default avatar
```

## 🚀 **Deployment Notes**

1. **Storage Link**: Ensure `php artisan storage:link` is run
2. **Permissions**: Set proper write permissions on storage directory
3. **Disk Configuration**: Verify public disk is configured correctly
4. **CDN**: Consider using CDN for production image serving

## 📈 **Future Enhancements**

1. **Image Resizing**: Automatic thumbnail generation
2. **Multiple Images**: Support for multiple profile pictures
3. **Cloud Storage**: Integration with AWS S3 or Google Cloud Storage
4. **Image Optimization**: Automatic compression and optimization
5. **Face Detection**: AI-powered face detection and cropping

---

This implementation provides a robust, secure, and user-friendly profile image upload system with proper validation, storage management, and URL generation.
