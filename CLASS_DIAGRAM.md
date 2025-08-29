# 🏗️ Class Diagram - Matrimonial API System

## 🎯 **System Overview**

This class diagram shows the complete object-oriented structure of the Matrimonial API system, including all main classes, their relationships, attributes, and methods.

## 🏗️ **Complete System Class Diagram**

```mermaid
classDiagram
    %% Core Models
    class User {
        +id: bigint
        +name: string
        +email: string
        +email_verified_at: timestamp
        +password: string
        +gender: enum
        +date_of_birth: date
        +phone: string
        +location: string
        +bio: text
        +profile_photo: string
        +fcm_token: string
        +notification_enabled: boolean
        +is_active: boolean
        +last_login_at: timestamp
        +created_at: timestamp
        +updated_at: timestamp
        +preferences()
        +likes()
        +likedBy()
        +matches()
        +matchedBy()
        +sentMessages()
        +receivedMessages()
        +notifications()
        +tokens()
        +canReceiveNotifications()
        +updateFcmToken()
        +enableNotifications()
        +disableNotifications()
    }

    class Preference {
        +id: bigint
        +user_id: bigint
        +min_age: int
        +max_age: int
        +preferred_gender: enum
        +religion: string
        +education_level: string
        +location_preference: string
        +max_distance: int
        +marital_status: string
        +occupation: string
        +income_range: string
        +family_type: string
        +created_at: timestamp
        +updated_at: timestamp
        +user()
    }

    class Match {
        +id: bigint
        +user_id: bigint
        +matched_user_id: bigint
        +match_date: timestamp
        +status: enum
        +created_at: timestamp
        +updated_at: timestamp
        +user()
        +matchedUser()
    }

    class Like {
        +id: bigint
        +user_id: bigint
        +liked_user_id: bigint
        +status: enum
        +created_at: timestamp
        +updated_at: timestamp
        +user()
        +likedUser()
    }

    class Message {
        +id: bigint
        +from_user_id: bigint
        +to_user_id: bigint
        +message: text
        +is_read: boolean
        +read_at: timestamp
        +message_type: enum
        +attachment_url: string
        +created_at: timestamp
        +updated_at: timestamp
        +fromUser()
        +toUser()
    }

    class Notification {
        +id: bigint
        +user_id: bigint
        +type: enum
        +title: string
        +body: text
        +data: json
        +is_read: boolean
        +read_at: timestamp
        +fcm_message_id: string
        +status: enum
        +error_message: text
        +created_at: timestamp
        +updated_at: timestamp
        +user()
        +scopeUnread()
        +scopeRead()
        +scopeOfType()
        +markAsRead()
        +markAsUnread()
    }

    class PersonalAccessToken {
        +id: bigint
        +tokenable_type: string
        +tokenable_id: bigint
        +name: string
        +token: string
        +abilities: text
        +last_used_at: timestamp
        +expires_at: timestamp
        +created_at: timestamp
        +updated_at: timestamp
        +tokenable()
    }

    %% Controllers
    class AuthController {
        +register()
        +login()
        +logout()
        +refresh()
        +me()
    }

    class UserController {
        +show()
        +update()
        +destroy()
        +updateProfile()
        +getPreferences()
        +updatePreferences()
    }

    class DiscoverController {
        +getDiscoverProfiles()
        +likeProfile()
        +unlikeProfile()
        +getLikedByProfiles()
    }

    class MatchController {
        +getMatches()
        +getMatch()
        +unmatch()
    }

    class MessageController {
        +getMessages()
        +sendMessage()
        +markAsRead()
        +deleteMessage()
    }

    class NotificationController {
        +getNotifications()
        +getUnreadCount()
        +markAsRead()
        +markAllAsRead()
        +deleteNotification()
        +deleteAllNotifications()
        +updateFcmToken()
        +toggleNotifications()
        +getNotificationSettings()
        +getNotificationStats()
        +testNotification()
    }

    class RecommendationController {
        +getRecommendations()
        +getCompatibilityScore()
    }

    %% Services
    class MatchmakingService {
        +getRecommendations()
        +calculateCompatibilityScore()
        +getDiscoverRecommendations()
        +applyFilters()
        +calculateWeightedScore()
    }

    class NotificationService {
        +sendMatchNotification()
        +sendMessageNotification()
        +sendLikeNotification()
        +sendProfileViewNotification()
        +sendCustomNotification()
        +sendBulkNotification()
        +getUserNotifications()
        +getUnreadCount()
        +markAsRead()
        +markAllAsRead()
        +deleteNotification()
        +deleteAllNotifications()
    }

    class FCMService {
        -projectId: string
        -client: GoogleClient
        +__construct()
        +sendNotification()
        +sendNotificationToMultiple()
        -getAccessToken()
        -createNotificationRecord()
    }

    %% Relationships
    User ||--|| Preference
    User ||--o{ Like
    User ||--o{ Match
    User ||--o{ Message
    User ||--o{ Notification
    User ||--o{ PersonalAccessToken

    Like ||--o{ Match
    Message ||--o{ Notification

    %% Controller Dependencies
    AuthController --> User
    UserController --> User
    UserController --> Preference
    DiscoverController --> User
    DiscoverController --> Like
    DiscoverController --> Match
    MatchController --> Match
    MessageController --> Message
    MessageController --> User
    NotificationController --> Notification
    NotificationController --> FCMService
    RecommendationController --> MatchmakingService

    %% Service Dependencies
    DiscoverController --> MatchmakingService
    DiscoverController --> NotificationService
    MessageController --> NotificationService
    NotificationService --> FCMService
    NotificationService --> Notification
```

## 📊 **Class Descriptions**

### **🎯 Core Models**

#### **User**
- **Purpose**: Central entity representing application users
- **Key Features**: Profile management, authentication, notification settings
- **Relationships**: One-to-one with Preference, many-to-many with other Users through Likes/Matches

#### **Preference**
- **Purpose**: Stores user matching preferences and criteria
- **Key Features**: Age range, gender preference, location, religion, etc.
- **Relationships**: One-to-one with User

#### **Match**
- **Purpose**: Represents mutual matches between users
- **Key Features**: Match status, creation date, bidirectional relationship
- **Relationships**: Many-to-many between Users

#### **Like**
- **Purpose**: Stores one-way likes between users
- **Key Features**: Like status, timestamp, mutual match detection
- **Relationships**: Many-to-many between Users

#### **Message**
- **Purpose**: Chat messages between matched users
- **Key Features**: Message content, read status, attachments
- **Relationships**: Many-to-many between Users (only matched users)

#### **Notification**
- **Purpose**: Push notification records and history
- **Key Features**: Notification types, delivery status, FCM integration
- **Relationships**: One-to-many with User

#### **PersonalAccessToken**
- **Purpose**: Laravel Sanctum authentication tokens
- **Key Features**: Token management, abilities, expiration
- **Relationships**: Polymorphic with User

### **🎮 Controllers**

#### **AuthController**
- **Purpose**: Handles user authentication and authorization
- **Key Methods**: Registration, login, logout, token refresh

#### **UserController**
- **Purpose**: Manages user profiles and preferences
- **Key Methods**: Profile CRUD operations, preference management

#### **DiscoverController**
- **Purpose**: Handles profile discovery and like system
- **Key Methods**: Get discover profiles, like/unlike, mutual match detection

#### **MatchController**
- **Purpose**: Manages user matches
- **Key Methods**: Get matches, unmatch users

#### **MessageController**
- **Purpose**: Handles chat functionality
- **Key Methods**: Send/receive messages, read status, conversation management

#### **NotificationController**
- **Purpose**: Manages push notifications
- **Key Methods**: Notification CRUD, FCM token management, settings

#### **RecommendationController**
- **Purpose**: Provides AI-powered recommendations
- **Key Methods**: Get recommendations, compatibility scoring

### **🔧 Services**

#### **MatchmakingService**
- **Purpose**: Core matching algorithm and recommendations
- **Key Features**: Collaborative filtering, weighted scoring, preference matching

#### **NotificationService**
- **Purpose**: Orchestrates notification logic
- **Key Features**: Notification generation, FCM integration, history management

#### **FCMService**
- **Purpose**: Direct Firebase Cloud Messaging integration
- **Key Features**: OAuth2 authentication, push notification delivery

## 🔗 **Relationship Types**

### **One-to-One**
- **User ↔ Preference**: Each user has exactly one preference record

### **One-to-Many**
- **User → Messages**: User can send/receive multiple messages
- **User → Notifications**: User can have multiple notifications
- **User → PersonalAccessTokens**: User can have multiple tokens

### **Many-to-Many**
- **Users ↔ Users (Likes)**: Users can like multiple users
- **Users ↔ Users (Matches)**: Users can match with multiple users
- **Users ↔ Users (Messages)**: Users can message multiple matched users

## 🔒 **Security & Validation**

### **Authentication**
- JWT token validation in all controllers
- Token expiration and refresh mechanisms
- Secure password hashing

### **Authorization**
- User can only access their own data
- Match verification for messaging
- Rate limiting and abuse prevention

### **Data Validation**
- Input sanitization and validation
- SQL injection prevention
- XSS protection

## 📈 **Performance Considerations**

### **Database Optimization**
- Strategic indexing on foreign keys
- Composite indexes for complex queries
- Query optimization for large datasets

### **Caching Strategy**
- User profile caching
- Match list caching
- Notification caching

### **API Optimization**
- Pagination for large datasets
- Response compression
- Efficient data serialization

## 🔄 **Design Patterns**

### **Service Layer Pattern**
- Business logic separated into services
- Controllers focus on HTTP handling
- Reusable service methods

### **Repository Pattern**
- Database operations abstracted
- Easy to switch database implementations
- Testable data access layer

### **Observer Pattern**
- Event-driven notifications
- Decoupled notification system
- Extensible event handling

## 📊 **Data Flow**

1. **Request** → Controller receives HTTP request
2. **Validation** → Input validation and authorization
3. **Service** → Business logic in service layer
4. **Model** → Database operations through models
5. **Response** → Formatted response to client

This class diagram provides a comprehensive view of the Matrimonial API system's object-oriented architecture, showing all major components and their relationships.
