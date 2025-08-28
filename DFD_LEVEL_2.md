# 📊 Data Flow Diagram (DFD) - Level 2

## 🎯 **Detailed Process Breakdown**

The Level 2 DFD shows the detailed sub-processes within each main process, providing a granular view of data flows and operations.

## 🔄 **DFD Level 2 Diagrams**

### **1.0 AUTHENTICATE & AUTHORIZE - Level 2**

```
┌─────────────────┐
│   Mobile App    │
│   (Frontend)    │
└─────────┬───────┘
          │
          ▼
┌─────────────────────────────────────────────────────────────────┐
│                   1.0 AUTHENTICATE & AUTHORIZE                  │
│                                                                 │
│  ┌─────────────┐    ┌─────────────┐    ┌─────────────┐         │
│  │  1.1 USER   │    │  1.2 TOKEN  │    │  1.3 SESSION│         │
│  │ REGISTRATION│    │ GENERATION  │    │ MANAGEMENT  │         │
│  │             │    │             │    │             │         │
│  │ • Validate  │    │ • Create    │    │ • Store     │         │
│  │   Input     │    │   JWT Token │    │   Session   │         │
│  │ • Hash      │    │ • Set       │    │ • Track     │         │
│  │   Password  │    │   Expiry    │    │   Activity  │         │
│  │ • Create    │    │ • Sign      │    │ • Handle    │         │
│  │   Profile   │    │   Token     │    │   Logout    │         │
│  └─────────────┘    └─────────────┘    └─────────────┘         │
│          │                   │                   │             │
│          ▼                   ▼                   ▼             │
│  ┌─────────────┐    ┌─────────────┐    ┌─────────────┐         │
│  │   USERS     │    │   TOKENS    │    │  SESSIONS   │         │
│  └─────────────┘    └─────────────┘    └─────────────┘         │
└─────────────────────────────────────────────────────────────────┘
```

### **2.0 MATCHMAKING & DISCOVER - Level 2**

```
┌─────────────────┐
│   Mobile App    │
│   (Frontend)    │
└─────────┬───────┘
          │
          ▼
┌─────────────────────────────────────────────────────────────────┐
│                   2.0 MATCHMAKING & DISCOVER                    │
│                                                                 │
│  ┌─────────────┐    ┌─────────────┐    ┌─────────────┐         │
│  │  2.1 PROFILE│    │  2.2 LIKE   │    │  2.3 MATCH  │         │
│  │ DISCOVERY   │    │ PROCESSING  │    │ CREATION    │         │
│  │             │    │             │    │             │         │
│  │ • Get User  │    │ • Validate  │    │ • Check     │         │
│  │   Preferences│   │   Like      │    │   Mutual    │         │
│  │ • Apply     │    │ • Store     │    │   Like      │         │
│  │   Filters   │    │   Like      │    │ • Create    │         │
│  │ • Calculate │    │ • Trigger   │    │   Match     │         │
│  │   Scores    │    │   Events    │    │ • Send      │         │
│  │ • Rank      │    │ • Update    │    │   Notif     │         │
│  │   Results   │    │   Stats     │    │ • Update    │         │
│  └─────────────┘    └─────────────┘    │   Status    │         │
│          │                   │         └─────────────┘         │
│          ▼                   ▼                   │             │
│  ┌─────────────┐    ┌─────────────┐    ┌─────────────┐         │
│  │ PREFERENCES │    │   LIKES     │    │   MATCHES   │         │
│  └─────────────┘    └─────────────┘    └─────────────┘         │
└─────────────────────────────────────────────────────────────────┘
```

### **3.0 MESSAGING & CHAT - Level 2**

```
┌─────────────────┐
│   Mobile App    │
│   (Frontend)    │
└─────────┬───────┘
          │
          ▼
┌─────────────────────────────────────────────────────────────────┐
│                     3.0 MESSAGING & CHAT                        │
│                                                                 │
│  ┌─────────────┐    ┌─────────────┐    ┌─────────────┐         │
│  │  3.1 MESSAGE│    │  3.2 CHAT   │    │  3.3 READ   │         │
│  │ VALIDATION  │    │ HISTORY     │    │ STATUS      │         │
│  │             │    │             │    │             │         │
│  │ • Validate  │    │ • Retrieve  │    │ • Mark      │         │
│  │   Content   │    │   Messages  │    │   Read      │         │
│  │ • Check     │    │ • Group by  │    │ • Update    │         │
│  │   Permissions│   │   Chat      │    │   Timestamp │         │
│  │ • Filter    │    │ • Sort by   │    │ • Send      │         │
│  │   Spam      │    │   Time      │    │   Receipt   │         │
│  │ • Sanitize  │    │ • Paginate  │    │ • Update    │         │
│  │   Input     │    │   Results   │    │   Counters  │         │
│  └─────────────┘    └─────────────┘    └─────────────┘         │
│          │                   │                   │             │
│          ▼                   ▼                   ▼             │
│  ┌─────────────┐    ┌─────────────┐    ┌─────────────┐         │
│  │  MESSAGES   │    │ CONVERSATIONS│   │ READ_STATUS │         │
│  └─────────────┘    └─────────────┘    └─────────────┘         │
└─────────────────────────────────────────────────────────────────┘
```

### **4.0 NOTIFICATION MANAGEMENT - Level 2**

```
┌─────────────────┐
│   Firebase FCM  │
│   (Push Notif)  │
└─────────┬───────┘
          │
          ▼
┌─────────────────────────────────────────────────────────────────┐
│                   4.0 NOTIFICATION MANAGEMENT                   │
│                                                                 │
│  ┌─────────────┐    ┌─────────────┐    ┌─────────────┐         │
│  │  4.1 FCM    │    │  4.2 NOTIF  │    │  4.3 BADGE  │         │
│  │ TOKEN MGMT  │    │ GENERATION  │    │ MANAGEMENT  │         │
│  │             │    │             │    │             │         │
│  │ • Register  │    │ • Create    │    │ • Calculate │         │
│  │   Token     │    │   Payload   │    │   Badge     │         │
│  │ • Update    │    │ • Format    │    │   Count     │         │
│  │   Token     │    │   Message   │    │ • Update    │         │
│  │ • Validate  │    │ • Add Data  │    │   App Badge │         │
│  │   Token     │    │ • Set       │    │ • Track     │         │
│  │ • Cleanup   │    │   Priority  │    │   History   │         │
│  │   Expired   │    │ • Schedule  │    │ • Reset     │         │
│  └─────────────┘    └─────────────┘    └─────────────┘         │
│          │                   │                   │             │
│          ▼                   ▼                   ▼             │
│  ┌─────────────┐    ┌─────────────┐    ┌─────────────┐         │
│  │ FCM_TOKENS  │    │NOTIFICATIONS│    │ BADGE_COUNTS│         │
│  └─────────────┘    └─────────────┘    └─────────────┘         │
└─────────────────────────────────────────────────────────────────┘
```

### **5.0 USER MANAGEMENT - Level 2**

```
┌─────────────────┐
│   Mobile App    │
│   (Frontend)    │
└─────────┬───────┘
          │
          ▼
┌─────────────────────────────────────────────────────────────────┐
│                     5.0 USER MANAGEMENT                         │
│                                                                 │
│  ┌─────────────┐    ┌─────────────┐    ┌─────────────┐         │
│  │  5.1 PROFILE│    │  5.2 PREF   │    │  5.3 ACCOUNT│         │
│  │ MANAGEMENT  │    │ MANAGEMENT  │    │ SETTINGS    │         │
│  │             │    │             │    │             │         │
│  │ • Update    │    │ • Set Age   │    │ • Privacy   │         │
│  │   Profile   │    │   Range     │    │   Settings  │         │
│  │ • Validate  │    │ • Religion  │    │ • Notification│       │
│  │   Data      │    │   Prefs     │    │   Settings  │         │
│  │ • Upload    │    │ • Location  │    │ • Account   │         │
│  │   Photos    │    │   Prefs     │    │   Status    │         │
│  │ • Manage    │    │ • Education │    │ • Security  │         │
│  │   Privacy   │    │   Prefs     │    │   Settings  │         │
│  └─────────────┘    └─────────────┘    └─────────────┘         │
│          │                   │                   │             │
│          ▼                   ▼                   ▼             │
│  ┌─────────────┐    ┌─────────────┐    ┌─────────────┐         │
│  │   USERS     │    │ PREFERENCES │    │   SETTINGS  │         │
│  └─────────────┘    └─────────────┘    └─────────────┘         │
└─────────────────────────────────────────────────────────────────┘
```

### **6.0 ANALYTICS & REPORTS - Level 2**

```
┌─────────────────┐
│   Admin Panel   │
│   (Dashboard)   │
└─────────┬───────┘
          │
          ▼
┌─────────────────────────────────────────────────────────────────┐
│                   6.0 ANALYTICS & REPORTS                       │
│                                                                 │
│  ┌─────────────┐    ┌─────────────┐    ┌─────────────┐         │
│  │  6.1 DATA   │    │  6.2 METRICS│    │  6.3 REPORT │         │
│  │ COLLECTION  │    │ CALCULATION │    │ GENERATION  │         │
│  │             │    │             │    │             │         │
│  │ • Track     │    │ • User      │    │ • Generate  │         │
│  │   Events    │    │   Activity  │    │   Reports   │         │
│  │ • Log       │    │ • Match     │    │ • Create    │         │
│  │   Actions   │    │   Success   │    │   Charts    │         │
│  │ • Monitor   │    │ • Message   │    │ • Export    │         │
│  │   Performance│   │   Volume    │    │   Data      │         │
│  │ • Store     │    │ • System    │    │ • Schedule  │         │
│  │   Analytics │    │   Health    │    │   Reports   │         │
│  └─────────────┘    └─────────────┘    └─────────────┘         │
│          │                   │                   │             │
│          ▼                   ▼                   ▼             │
│  ┌─────────────┐    ┌─────────────┐    ┌─────────────┐         │
│  │   EVENTS    │    │   METRICS   │    │   REPORTS   │         │
│  └─────────────┘    └─────────────┘    └─────────────┘         │
└─────────────────────────────────────────────────────────────────┘
```

## 📋 **Detailed Process Descriptions**

### **1.0 AUTHENTICATE & AUTHORIZE - Sub-processes**

#### **1.1 USER REGISTRATION**
- **Input**: Registration form data, email, password
- **Process**: Input validation, password hashing, profile creation
- **Output**: New user account, validation results
- **Data Stores**: USERS

#### **1.2 TOKEN GENERATION**
- **Input**: User credentials, authentication request
- **Process**: JWT token creation, expiry setting, token signing
- **Output**: Authentication token, refresh token
- **Data Stores**: TOKENS

#### **1.3 SESSION MANAGEMENT**
- **Input**: User session data, activity logs
- **Process**: Session tracking, activity monitoring, logout handling
- **Output**: Session status, activity reports
- **Data Stores**: SESSIONS

### **2.0 MATCHMAKING & DISCOVER - Sub-processes**

#### **2.1 PROFILE DISCOVERY**
- **Input**: User preferences, filters, search criteria
- **Process**: Preference matching, score calculation, result ranking
- **Output**: Discover profiles, compatibility scores
- **Data Stores**: USERS, PREFERENCES

#### **2.2 LIKE PROCESSING**
- **Input**: Like action, user IDs, timestamp
- **Process**: Like validation, storage, event triggering
- **Output**: Like confirmation, match check trigger
- **Data Stores**: LIKES

#### **2.3 MATCH CREATION**
- **Input**: Mutual like detection, user data
- **Process**: Match validation, notification generation, status update
- **Output**: Match creation, notifications
- **Data Stores**: MATCHES, NOTIFICATIONS

### **3.0 MESSAGING & CHAT - Sub-processes**

#### **3.1 MESSAGE VALIDATION**
- **Input**: Message content, sender/receiver data
- **Process**: Content validation, spam detection, permission check
- **Output**: Validated message, error reports
- **Data Stores**: MESSAGES

#### **3.2 CHAT HISTORY**
- **Input**: User IDs, conversation data, pagination
- **Process**: Message retrieval, conversation grouping, sorting
- **Output**: Chat history, conversation lists
- **Data Stores**: MESSAGES, CONVERSATIONS

#### **3.3 READ STATUS**
- **Input**: Message read events, user actions
- **Process**: Read status update, timestamp recording, counter updates
- **Output**: Updated read status, unread counts
- **Data Stores**: READ_STATUS, MESSAGES

### **4.0 NOTIFICATION MANAGEMENT - Sub-processes**

#### **4.1 FCM TOKEN MANAGEMENT**
- **Input**: FCM tokens, device information, user preferences
- **Process**: Token registration, validation, cleanup
- **Output**: Registered tokens, validation results
- **Data Stores**: FCM_TOKENS, USERS

#### **4.2 NOTIFICATION GENERATION**
- **Input**: Notification events, user data, message content
- **Process**: Payload creation, formatting, scheduling
- **Output**: Notification payloads, delivery status
- **Data Stores**: NOTIFICATIONS

#### **4.3 BADGE MANAGEMENT**
- **Input**: Notification counts, user actions, app state
- **Process**: Badge calculation, app badge updates, history tracking
- **Output**: Badge counts, update confirmations
- **Data Stores**: BADGE_COUNTS, NOTIFICATIONS

### **5.0 USER MANAGEMENT - Sub-processes**

#### **5.1 PROFILE MANAGEMENT**
- **Input**: Profile updates, photo uploads, user data
- **Process**: Data validation, photo processing, privacy checks
- **Output**: Updated profiles, validation results
- **Data Stores**: USERS

#### **5.2 PREFERENCE MANAGEMENT**
- **Input**: Preference changes, filter updates, weight adjustments
- **Process**: Preference validation, weight calculation, filter application
- **Output**: Updated preferences, matching criteria
- **Data Stores**: PREFERENCES

#### **5.3 ACCOUNT SETTINGS**
- **Input**: Account settings, privacy preferences, notification settings
- **Process**: Settings validation, privacy enforcement, notification configuration
- **Output**: Updated settings, configuration confirmations
- **Data Stores**: SETTINGS, USERS

### **6.0 ANALYTICS & REPORTS - Sub-processes**

#### **6.1 DATA COLLECTION**
- **Input**: User interactions, system events, performance data
- **Process**: Event tracking, data logging, performance monitoring
- **Output**: Analytics data, event logs
- **Data Stores**: EVENTS, LOGS

#### **6.2 METRICS CALCULATION**
- **Input**: Raw analytics data, business metrics, performance indicators
- **Process**: Data aggregation, metric calculation, trend analysis
- **Output**: Calculated metrics, trend reports
- **Data Stores**: METRICS, ANALYTICS

#### **6.3 REPORT GENERATION**
- **Input**: Calculated metrics, report templates, export requests
- **Process**: Report creation, chart generation, data export
- **Output**: Generated reports, charts, exported data
- **Data Stores**: REPORTS, EXPORTS

## 🔄 **Data Flow Patterns**

### **Synchronous Flows**
- User authentication
- Message sending
- Profile updates
- Like processing

### **Asynchronous Flows**
- Push notification delivery
- Analytics processing
- Report generation
- Background tasks

### **Event-Driven Flows**
- Match creation triggers
- Notification events
- Analytics tracking
- System monitoring

## 💾 **Additional Data Stores**

### **FCM_TOKENS**
- Device tokens, user associations, token status
- Primary key: token_id

### **CONVERSATIONS**
- Chat conversations, participant lists, metadata
- Primary key: conversation_id

### **READ_STATUS**
- Message read status, timestamps, user actions
- Primary key: read_id

### **BADGE_COUNTS**
- Notification badge counts, app states, update history
- Primary key: badge_id

### **SETTINGS**
- User settings, privacy preferences, notification configs
- Primary key: setting_id

### **EVENTS**
- System events, user actions, analytics data
- Primary key: event_id

### **METRICS**
- Calculated metrics, performance indicators, business KPIs
- Primary key: metric_id

### **REPORTS**
- Generated reports, charts, export data
- Primary key: report_id
