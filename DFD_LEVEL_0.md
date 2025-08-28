# 📊 Data Flow Diagram (DFD) - Level 0

## 🎯 **System Context Overview**

The Level 0 DFD (Context Diagram) shows the Matrimonial API system as a single process with its external entities and data stores. This is the highest-level view of the system.

## 🔄 **DFD Level 0 Diagram**

```
┌─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┐
│                                                                                                                     │
│  ┌─────────────────┐                                                                                                 │
│  │   Mobile App    │                                                                                                 │
│  │   (Frontend)    │                                                                                                 │
│  │                 │                                                                                                 │
│  │ • User Interface│                                                                                                 │
│  │ • Profile Mgmt  │                                                                                                 │
│  │ • Chat Interface│                                                                                                 │
│  │ • Notifications │                                                                                                 │
│  └─────────┬───────┘                                                                                                 │
│            │                                                                                                         │
│            │ User Actions, Auth Requests, Messages, Profile Updates                                                  │
│            │                                                                                                         │
│            ▼                                                                                                         │
│  ┌─────────────────────────────────────────────────────────────────────────────────────────────────────────────────┐ │
│  │                                                                                                                 │ │
│  │                           MATRIMONIAL API SYSTEM                                                               │ │
│  │                                                                                                                 │ │
│  │  ┌─────────────────────────────────────────────────────────────────────────────────────────────────────────────┐ │ │
│  │  │                                                                                                             │ │ │
│  │  │  • User Authentication & Authorization                                                                     │ │ │
│  │  │  • Profile Management & Discovery                                                                          │ │ │
│  │  │  • Matchmaking & Recommendation Engine                                                                     │ │ │
│  │  │  • Like System & Mutual Match Creation                                                                     │ │ │
│  │  │  • Messaging & Chat System                                                                                 │ │ │
│  │  │  • Push Notification Management (FCM)                                                                      │ │ │
│  │  │  • Analytics & Reporting                                                                                   │ │ │
│  │  │  • Preference Management                                                                                   │ │ │
│  │  │  • Security & Privacy Controls                                                                             │ │ │
│  │  │                                                                                                             │ │ │
│  │  └─────────────────────────────────────────────────────────────────────────────────────────────────────────────┘ │ │
│  │                                                                                                                 │ │
│  └─────────────────────────────────────────────────────────────────────────────────────────────────────────────────┘ │
│            │                                                                                                         │
│            │ Recommendations, Matches, Messages, Notifications, User Data                                          │
│            │                                                                                                         │
│            ▼                                                                                                         │
│  ┌─────────────────┐                                                                                                 │
│  │   Mobile App    │                                                                                                 │
│  │   (Frontend)    │                                                                                                 │
│  │                 │                                                                                                 │
│  │ • Display Data  │                                                                                                 │
│  │ • Show Matches  │                                                                                                 │
│  │ • Chat History  │                                                                                                 │
│  │ • Notifications │                                                                                                 │
│  └─────────────────┘                                                                                                 │
│                                                                                                                     │
│  ┌─────────────────┐                                                                                                 │
│  │   Firebase FCM  │                                                                                                 │
│  │   (Push Notif)  │                                                                                                 │
│  │                 │                                                                                                 │
│  │ • Push Delivery │                                                                                                 │
│  │ • Token Mgmt    │                                                                                                 │
│  │ • Device Notif  │                                                                                                 │
│  └─────────┬───────┘                                                                                                 │
│            │                                                                                                         │
│            │ Notification Payloads, FCM Tokens                                                                      │
│            │                                                                                                         │
│            ▼                                                                                                         │
│  ┌─────────────────────────────────────────────────────────────────────────────────────────────────────────────────┐ │
│  │                                                                                                                 │ │
│  │                           MATRIMONIAL API SYSTEM                                                               │ │
│  │                                                                                                                 │ │
│  └─────────────────────────────────────────────────────────────────────────────────────────────────────────────────┘ │
│            │                                                                                                         │
│            │ Delivery Confirmations, Error Reports                                                                 │
│            │                                                                                                         │
│            ▼                                                                                                         │
│  ┌─────────────────┐                                                                                                 │
│  │   Firebase FCM  │                                                                                                 │
│  │   (Push Notif)  │                                                                                                 │
│  └─────────────────┘                                                                                                 │
│                                                                                                                     │
└─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┘
```

## 💾 **Data Stores**

```
┌─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┐
│                                         DATA STORES                                                                  │
│                                                                                                                     │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐              │
│  │   USERS     │  │ PREFERENCES │  │   MATCHES   │  │   LIKES     │  │  MESSAGES   │  │NOTIFICATIONS│              │
│  │             │  │             │  │             │  │             │  │             │  │             │              │
│  │ • User Info │  │ • Age Range │  │ • Mutual    │  │ • One-way   │  │ • Chat      │  │ • Push      │              │
│  │ • Profiles  │  │ • Religion  │  │   Matches   │  │   Likes     │  │   History   │  │   Notifs    │              │
│  │ • Auth Data │  │ • Location  │  │ • Match     │  │ • Like      │  │ • Messages  │  │ • FCM Tokens│              │
│  │ • FCM Token │  │ • Education │  │   Status    │  │   Timestamp │  │ • Read      │  │ • Settings  │              │
│  │ • Settings  │  │ • Weights   │  │ • Created   │  │ • User IDs  │  │   Status    │  │ • History   │              │
│  │ • Privacy   │  │ • Filters   │  │   Date      │  │ • Status    │  │ • Timestamp │  │ • Badge     │              │
│  └─────────────┘  └─────────────┘  └─────────────┘  └─────────────┘  └─────────────┘  └─────────────┘              │
│                                                                                                                     │
└─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┘
```

## 🔗 **External Entities**

### **Mobile App (Frontend)**

-   **Role**: Primary user interface and interaction point
-   **Platform**: React Native/Expo application
-   **Key Functions**:
    -   User registration and login
    -   Profile creation and management
    -   Discover and like profiles
    -   View matches and chat
    -   Receive push notifications
    -   Manage preferences and settings

### **Firebase FCM (Push Notifications)**

-   **Role**: External push notification service
-   **Provider**: Google Firebase Cloud Messaging
-   **Key Functions**:
    -   Device token management
    -   Push notification delivery
    -   Cross-platform notification support
    -   Delivery tracking and reporting

## 🔄 **Data Flows**

### **From Mobile App to System**

1. **User Registration Data**

    - Name, email, password, profile information
    - Photo uploads, personal details
    - Registration preferences

2. **Authentication Requests**

    - Login credentials
    - Token refresh requests
    - Logout actions

3. **User Actions**

    - Profile updates and edits
    - Like/unlike actions
    - Message sending
    - Preference changes
    - FCM token registration

4. **Discovery Requests**
    - Profile discovery requests
    - Search and filter criteria
    - Pagination requests

### **From System to Mobile App**

1. **Authentication Responses**

    - JWT tokens, user session data
    - Validation results, error messages

2. **User Data**

    - Profile information, preferences
    - Updated user settings
    - Privacy controls

3. **Matchmaking Results**

    - Discover profiles with compatibility scores
    - Match notifications and updates
    - Like confirmations

4. **Communication Data**

    - Chat messages and history
    - Conversation lists
    - Unread message counts

5. **System Notifications**
    - Push notification payloads
    - Badge count updates
    - System announcements

### **From System to Firebase FCM**

1. **Notification Payloads**

    - Message content, titles, bodies
    - User data, action URLs
    - Priority settings, scheduling

2. **FCM Token Management**
    - Token registration requests
    - Token validation and cleanup
    - Device association data

### **From Firebase FCM to System**

1. **Delivery Confirmations**

    - Success/failure reports
    - Delivery timestamps
    - Error details and retry information

2. **Token Status Updates**
    - Token validity confirmations
    - Expired token notifications
    - Device unregistration events

## 🎯 **System Boundaries**

### **Inside the System**

-   All business logic and processing
-   Database operations and data management
-   Authentication and authorization
-   Matchmaking algorithms
-   Notification generation
-   Analytics and reporting

### **Outside the System**

-   Mobile app frontend (React Native/Expo)
-   Firebase FCM service
-   User devices and browsers
-   External APIs (if any)
-   Third-party services

## 📊 **Key System Characteristics**

### **Core Functions**

-   **User Management**: Registration, authentication, profile management
-   **Matchmaking**: Discovery, recommendations, mutual matching
-   **Communication**: Messaging, chat, notifications
-   **Analytics**: User behavior tracking, reporting, insights

### **Data Processing**

-   **Real-time**: Authentication, messaging, like processing
-   **Batch**: Analytics, reporting, cleanup tasks
-   **Event-driven**: Match creation, notification triggers

### **Security & Privacy**

-   **Authentication**: JWT tokens, session management
-   **Authorization**: Role-based access control
-   **Data Protection**: Encrypted storage, privacy controls
-   **API Security**: Rate limiting, input validation

## 🔄 **System Interactions**

### **Primary User Journey**

1. **Registration** → Mobile App → System → User Creation
2. **Profile Setup** → Mobile App → System → Profile Storage
3. **Discovery** → Mobile App → System → Recommendations
4. **Like Action** → Mobile App → System → Match Check
5. **Match Creation** → System → Notification → Mobile App
6. **Messaging** → Mobile App → System → Chat History
7. **Notifications** → System → FCM → Mobile App

### **System Maintenance**

-   **Data Cleanup**: Expired tokens, old notifications
-   **Analytics Processing**: User behavior analysis
-   **Performance Monitoring**: System health checks
-   **Backup Operations**: Data backup and recovery

This Level 0 DFD provides the highest-level view of the Matrimonial API system, showing it as a single cohesive unit that interacts with external entities and manages internal data stores.
