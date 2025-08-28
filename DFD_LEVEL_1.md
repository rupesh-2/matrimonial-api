# 📊 Data Flow Diagram (DFD) - Level 1

## 🎯 **System Overview**

The Level 1 DFD shows the main processes and data stores in the Matrimonial API system, focusing on the primary data flows between external entities and the core system.

## 🔄 **DFD Level 1 Diagram**

```
┌─────────────────┐    ┌─────────────────────────────────────────────────────────────┐    ┌─────────────────┐
│                 │    │                     MATRIMONIAL API SYSTEM                  │    │                 │
│   Mobile App    │◄──►│                                                             │◄──►│   Firebase FCM   │
│   (Frontend)    │    │  ┌─────────────────┐  ┌─────────────────┐  ┌─────────────┐ │    │   (Push Notif)  │
│                 │    │  │  1.0 AUTHENTICATE│  │  2.0 MATCHMAKING│  │ 3.0 MESSAGING│ │    │                 │
│                 │    │  │   & AUTHORIZE    │  │   & DISCOVER    │  │   & CHAT     │ │    │                 │
│                 │    │  └─────────────────┘  └─────────────────┘  └─────────────┘ │    │                 │
│                 │    │                                                             │    │                 │
│                 │    │  ┌─────────────────┐  ┌─────────────────┐  ┌─────────────┐ │    │                 │
│                 │    │  │  4.0 NOTIFICATION│  │  5.0 USER       │  │ 6.0 ANALYTICS│ │    │                 │
│                 │    │  │   MANAGEMENT     │  │   MANAGEMENT    │  │   & REPORTS  │ │    │                 │
│                 │    │  └─────────────────┘  └─────────────────┘  └─────────────┘ │    │                 │
│                 │    └─────────────────────────────────────────────────────────────┘    │                 │
└─────────────────┘                                                                        └─────────────────┘
         │                                                                                         │
         │                                                                                         │
         ▼                                                                                         ▼
┌─────────────────────────────────────────────────────────────────────────────────────────────────────────────┐
│                                         DATA STORES                                                          │
│                                                                                                             │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐    │
│  │   USERS     │  │ PREFERENCES │  │   MATCHES   │  │   LIKES     │  │  MESSAGES   │  │NOTIFICATIONS│    │
│  │             │  │             │  │             │  │             │  │             │  │             │    │
│  │ • User Info │  │ • Age Range │  │ • Mutual    │  │ • One-way   │  │ • Chat      │  │ • Push      │    │
│  │ • Profiles  │  │ • Religion  │  │   Matches   │  │   Likes     │  │   History   │  │   Notifs    │    │
│  │ • Auth Data │  │ • Location  │  │ • Match     │  │ • Like      │  │ • Messages  │  │ • FCM Tokens│    │
│  │ • FCM Token │  │ • Education │  │   Status    │  │   Timestamp │  │ • Read      │  │ • Settings  │    │
│  └─────────────┘  └─────────────┘  └─────────────┘  └─────────────┘  └─────────────┘  └─────────────┘    │
└─────────────────────────────────────────────────────────────────────────────────────────────────────────────┘
```

## 📋 **Process Descriptions**

### **1.0 AUTHENTICATE & AUTHORIZE**
- **Input**: User credentials, registration data
- **Process**: User authentication, token generation, authorization
- **Output**: Authentication tokens, user session data
- **Data Stores**: USERS

### **2.0 MATCHMAKING & DISCOVER**
- **Input**: User preferences, profile data, like actions
- **Process**: Collaborative filtering, recommendation algorithm, match creation
- **Output**: Discover profiles, match notifications, compatibility scores
- **Data Stores**: USERS, PREFERENCES, MATCHES, LIKES

### **3.0 MESSAGING & CHAT**
- **Input**: Message content, sender/receiver IDs
- **Process**: Message validation, chat history management, read status
- **Output**: Chat messages, conversation lists, unread counts
- **Data Stores**: MESSAGES, USERS

### **4.0 NOTIFICATION MANAGEMENT**
- **Input**: FCM tokens, notification events, user preferences
- **Process**: Push notification generation, FCM token management, notification history
- **Output**: Push notifications, notification records, badge counts
- **Data Stores**: NOTIFICATIONS, USERS

### **5.0 USER MANAGEMENT**
- **Input**: Profile updates, preference changes, account settings
- **Process**: Profile validation, preference processing, account management
- **Output**: Updated profiles, preference changes, account status
- **Data Stores**: USERS, PREFERENCES

### **6.0 ANALYTICS & REPORTS**
- **Input**: User interactions, system events, performance metrics
- **Process**: Data aggregation, analytics processing, report generation
- **Output**: Analytics reports, performance metrics, insights
- **Data Stores**: All data stores

## 🔗 **External Entities**

### **Mobile App (Frontend)**
- **Role**: Primary user interface
- **Data Flows**: 
  - Sends: User actions, authentication requests, messages
  - Receives: Recommendations, matches, notifications, chat data

### **Firebase FCM (Push Notifications)**
- **Role**: Push notification delivery service
- **Data Flows**:
  - Receives: Notification payloads, FCM tokens
  - Sends: Delivery confirmations, error reports

## 💾 **Data Stores**

### **USERS**
- User profiles, authentication data, FCM tokens
- Primary key: user_id

### **PREFERENCES**
- User matching preferences, weights, filters
- Primary key: user_id

### **MATCHES**
- Mutual matches between users, match status
- Primary key: match_id

### **LIKES**
- One-way likes, like timestamps
- Primary key: like_id

### **MESSAGES**
- Chat messages, conversation data, read status
- Primary key: message_id

### **NOTIFICATIONS**
- Push notification records, FCM tokens, notification settings
- Primary key: notification_id

## 🔄 **Key Data Flows**

1. **User Registration/Login** → Authentication → User Session
2. **Profile Updates** → User Management → Updated Profile
3. **Like Actions** → Matchmaking → Match Creation/Notification
4. **Message Sending** → Messaging → Chat History
5. **System Events** → Notification Management → Push Notifications
6. **User Interactions** → Analytics → Reports & Insights
