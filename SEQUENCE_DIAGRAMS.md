# Matrimonial API - Sequence Diagrams

## 🎯 Sequence Diagram Overview

This document contains comprehensive sequence diagrams showing the key interactions and data flows in the Matrimonial API system. Each diagram illustrates the communication between different system components and actors.

## 📊 User Registration Sequence

```mermaid
sequenceDiagram
    participant U as User
    participant C as Client App
    participant LB as Load Balancer
    participant API as API Gateway
    participant AC as AuthController
    participant VS as ValidationService
    participant UM as User Model
    participant PM as Preference Model
    participant DB as MySQL Database
    participant TM as Token Manager

    U->>C: Fill registration form
    C->>LB: POST /api/register
    LB->>API: Forward request
    API->>AC: Route to AuthController
    AC->>VS: Validate input data
    VS-->>AC: Validation result

    alt Validation fails
        AC-->>API: Return validation errors
        API-->>LB: 422 Bad Request
        LB-->>C: Error response
        C-->>U: Show error messages
    else Validation passes
        AC->>UM: Check email uniqueness
        UM->>DB: SELECT email FROM users
        DB-->>UM: Email not found
        UM-->>AC: Email available

        AC->>UM: Create user record
        UM->>DB: INSERT INTO users
        DB-->>UM: User created (ID: 123)
        UM-->>AC: User created successfully

        AC->>PM: Create default preferences
        PM->>DB: INSERT INTO preferences
        DB-->>PM: Preferences created
        PM-->>AC: Preferences created

        AC->>TM: Generate auth token
        TM->>DB: INSERT INTO personal_access_tokens
        DB-->>TM: Token stored
        TM-->>AC: Token generated

        AC-->>API: Return user data + token
        API-->>LB: 201 Created
        LB-->>C: Success response
        C-->>U: Show success + redirect to dashboard
    end
```

## 🔐 User Login Sequence

```mermaid
sequenceDiagram
    participant U as User
    participant C as Client App
    participant LB as Load Balancer
    participant API as API Gateway
    participant AC as AuthController
    participant VS as ValidationService
    participant UM as User Model
    participant DB as MySQL Database
    participant TM as Token Manager

    U->>C: Enter email & password
    C->>LB: POST /api/login
    LB->>API: Forward request
    API->>AC: Route to AuthController
    AC->>VS: Validate credentials format
    VS-->>AC: Validation result

    alt Invalid format
        AC-->>API: Return validation errors
        API-->>LB: 422 Bad Request
        LB-->>C: Error response
        C-->>U: Show error messages
    else Valid format
        AC->>UM: Find user by email
        UM->>DB: SELECT * FROM users WHERE email = ?
        DB-->>UM: User record found

        AC->>UM: Verify password
        UM->>UM: Hash provided password
        UM->>UM: Compare with stored hash
        UM-->>AC: Password verified

        AC->>TM: Generate new token
        TM->>DB: INSERT INTO personal_access_tokens
        DB-->>TM: Token stored
        TM-->>AC: New token generated

        AC-->>API: Return user data + token
        API-->>LB: 200 OK
        LB-->>C: Success response
        C-->>U: Store token & redirect to dashboard
    end
```

## 👤 Profile Management Sequence

```mermaid
sequenceDiagram
    participant U as User
    participant C as Client App
    participant LB as Load Balancer
    participant API as API Gateway
    participant UC as UserController
    participant VS as ValidationService
    participant UM as User Model
    participant PM as Preference Model
    participant DB as MySQL Database

    U->>C: Update profile information
    C->>LB: PUT /api/profile
    LB->>API: Forward request with auth token
    API->>API: Validate auth token
    API->>UC: Route to UserController
    UC->>VS: Validate profile data
    VS-->>UC: Validation result

    alt Validation fails
        UC-->>API: Return validation errors
        API-->>LB: 422 Bad Request
        LB-->>C: Error response
        C-->>U: Show error messages
    else Validation passes
        UC->>UM: Update user profile
        UM->>DB: UPDATE users SET ...
        DB-->>UM: Profile updated
        UM-->>UC: Profile updated successfully

        UC-->>API: Return updated user data
        API-->>LB: 200 OK
        LB-->>C: Success response
        C-->>U: Show updated profile
    end
```

## 🎯 Preference Management Sequence

```mermaid
sequenceDiagram
    participant U as User
    participant C as Client App
    participant LB as Load Balancer
    participant API as API Gateway
    participant UC as UserController
    participant VS as ValidationService
    participant PM as Preference Model
    participant DB as MySQL Database

    U->>C: Set matchmaking preferences
    C->>LB: POST /api/profile/preferences
    LB->>API: Forward request with auth token
    API->>API: Validate auth token
    API->>UC: Route to UserController
    UC->>VS: Validate preference data
    VS-->>UC: Validation result

    alt Validation fails
        UC-->>API: Return validation errors
        API-->>LB: 422 Bad Request
        LB-->>C: Error response
        C-->>U: Show error messages
    else Validation passes
        UC->>PM: Update user preferences
        PM->>DB: UPDATE preferences SET ...
        DB-->>PM: Preferences updated
        PM-->>UC: Preferences updated successfully

        UC-->>API: Return updated preferences
        API-->>LB: 200 OK
        LB-->>C: Success response
        C-->>U: Show updated preferences
    end
```

## 🔍 Matchmaking Recommendations Sequence

```mermaid
sequenceDiagram
    participant U as User
    participant C as Client App
    participant LB as Load Balancer
    participant API as API Gateway
    participant RC as RecommendationController
    participant MS as MatchmakingService
    participant UM as User Model
    participant PM as Preference Model
    participant LM as Like Model
    participant DB as MySQL Database
    participant Redis as Redis Cache

    U->>C: Request recommendations
    C->>LB: GET /api/recommendations
    LB->>API: Forward request with auth token
    API->>API: Validate auth token
    API->>RC: Route to RecommendationController

    RC->>MS: Get recommendations for user
    MS->>Redis: Check cache for recommendations

    alt Cache hit
        Redis-->>MS: Return cached recommendations
        MS-->>RC: Return recommendations
    else Cache miss
        MS->>PM: Get user preferences
        PM->>DB: SELECT * FROM preferences WHERE user_id = ?
        DB-->>PM: User preferences
        PM-->>MS: Preferences data

        MS->>UM: Get eligible users
        UM->>DB: SELECT * FROM users WHERE ...
        DB-->>UM: Eligible users list
        UM-->>MS: Users data

        MS->>LM: Get user likes for collaborative filtering
        LM->>DB: SELECT * FROM likes WHERE user_id = ?
        DB-->>LM: User likes data
        LM-->>MS: Likes data

        MS->>MS: Calculate compatibility scores
        MS->>MS: Apply collaborative filtering
        MS->>MS: Rank recommendations

        MS->>Redis: Cache recommendations
        Redis-->>MS: Cache stored
        MS-->>RC: Return recommendations
    end

    RC-->>API: Return recommendations
    API-->>LB: 200 OK
    LB-->>C: Success response
    C-->>U: Display recommendations
```

## ❤️ Like User Sequence

```mermaid
sequenceDiagram
    participant U as User
    participant C as Client App
    participant LB as Load Balancer
    participant API as API Gateway
    participant LC as LikeController
    participant VS as ValidationService
    participant LM as Like Model
    participant MM as Match Model
    participant NS as NotificationService
    participant DB as MySQL Database

    U->>C: Like a user profile
    C->>LB: POST /api/likes/{user_id}
    LB->>API: Forward request with auth token
    API->>API: Validate auth token
    API->>LC: Route to LikeController
    LC->>VS: Validate target user exists
    VS-->>LC: Validation result

    alt Target user not found
        LC-->>API: Return error
        API-->>LB: 404 Not Found
        LB-->>C: Error response
        C-->>U: Show error message
    else Target user exists
        LC->>LM: Create like record
        LM->>DB: INSERT INTO likes
        DB-->>LM: Like created
        LM-->>LC: Like created successfully

        LC->>LM: Check for mutual like
        LM->>DB: SELECT * FROM likes WHERE ...
        DB-->>LM: Check result

        alt Mutual like found
            LC->>MM: Create match
            MM->>DB: INSERT INTO matches
            DB-->>MM: Match created
            MM-->>LC: Match created

            LC->>NS: Send match notifications
            NS->>NS: Send notification to both users
            NS-->>LC: Notifications sent

            LC-->>API: Return match created
            API-->>LB: 201 Created
            LB-->>C: Success response
            C-->>U: Show match notification
        else No mutual like
            LC-->>API: Return like created
            API-->>LB: 201 Created
            LB-->>C: Success response
            C-->>U: Show like confirmation
        end
    end
```

## 💬 Messaging Sequence

```mermaid
sequenceDiagram
    participant U1 as User 1 (Sender)
    participant C1 as Client App 1
    participant LB as Load Balancer
    participant API as API Gateway
    participant MC as MessageController
    participant VS as ValidationService
    participant MM as Message Model
    participant MM2 as Match Model
    participant NS as NotificationService
    participant DB as MySQL Database

    U1->>C1: Send message to match
    C1->>LB: POST /api/messages/send
    LB->>API: Forward request with auth token
    API->>API: Validate auth token
    API->>MC: Route to MessageController
    MC->>VS: Validate message data
    VS-->>MC: Validation result

    alt Validation fails
        MC-->>API: Return validation errors
        API-->>LB: 422 Bad Request
        LB-->>C1: Error response
        C1-->>U1: Show error messages
    else Validation passes
        MC->>MM2: Verify match exists
        MM2->>DB: SELECT * FROM matches WHERE ...
        DB-->>MM2: Match found
        MM2-->>MC: Match verified

        MC->>MM: Create message record
        MM->>DB: INSERT INTO messages
        DB-->>MM: Message created
        MM-->>MC: Message created successfully

        MC->>NS: Send notification to recipient
        NS->>NS: Send push/email notification
        NS-->>MC: Notification sent

        MC-->>API: Return message created
        API-->>LB: 201 Created
        LB-->>C1: Success response
        C1-->>U1: Show message sent confirmation
    end
```

## 💬 View Conversation Sequence

```mermaid
sequenceDiagram
    participant U as User
    participant C as Client App
    participant LB as Load Balancer
    participant API as API Gateway
    participant MC as MessageController
    participant MM as Message Model
    participant UM as User Model
    participant DB as MySQL Database

    U->>C: View conversation with match
    C->>LB: GET /api/messages/{user_id}
    LB->>API: Forward request with auth token
    API->>API: Validate auth token
    API->>MC: Route to MessageController

    MC->>MM: Get conversation messages
    MM->>DB: SELECT * FROM messages WHERE ...
    DB-->>MM: Messages list
    MM-->>MC: Messages data

    MC->>UM: Get sender/receiver names
    UM->>DB: SELECT name FROM users WHERE id IN (...)
    DB-->>UM: User names
    UM-->>MC: Names data

    MC->>MM: Mark messages as read
    MM->>DB: UPDATE messages SET is_read = true WHERE ...
    DB-->>MM: Messages marked as read
    MM-->>MC: Read status updated

    MC-->>API: Return conversation data
    API-->>LB: 200 OK
    LB-->>C: Success response
    C-->>U: Display conversation
```

## 🔍 View Matches Sequence

```mermaid
sequenceDiagram
    participant U as User
    participant C as Client App
    participant LB as Load Balancer
    participant API as API Gateway
    participant MC as MatchController
    participant MM as Match Model
    participant UM as User Model
    participant DB as MySQL Database

    U->>C: View all matches
    C->>LB: GET /api/matches
    LB->>API: Forward request with auth token
    API->>API: Validate auth token
    API->>MC: Route to MatchController

    MC->>MM: Get user matches
    MM->>DB: SELECT * FROM matches WHERE user_id = ?
    DB-->>MM: Matches list
    MM-->>MC: Matches data

    MC->>UM: Get matched users' profiles
    UM->>DB: SELECT * FROM users WHERE id IN (...)
    DB-->>UM: Matched users' profiles
    UM-->>MC: Profiles data

    MC-->>API: Return matches with user profiles
    API-->>LB: 200 OK
    LB-->>C: Success response
    C-->>U: Display matches list
```

## 🚪 User Logout Sequence

```mermaid
sequenceDiagram
    participant U as User
    participant C as Client App
    participant LB as Load Balancer
    participant API as API Gateway
    participant AC as AuthController
    participant TM as Token Manager
    participant DB as MySQL Database

    U->>C: Click logout
    C->>LB: POST /api/logout
    LB->>API: Forward request with auth token
    API->>API: Extract token from header
    API->>AC: Route to AuthController

    AC->>TM: Invalidate token
    TM->>DB: DELETE FROM personal_access_tokens WHERE token = ?
    DB-->>TM: Token deleted
    TM-->>AC: Token invalidated

    AC-->>API: Return logout confirmation
    API-->>LB: 200 OK
    LB-->>C: Success response
    C-->>U: Clear local token & redirect to login
```

## 🔄 Real-time Notification Sequence

```mermaid
sequenceDiagram
    participant U1 as User 1
    participant U2 as User 2
    participant C1 as Client App 1
    participant C2 as Client App 2
    participant API as API Gateway
    participant NS as NotificationService
    participant WS as WebSocket Server
    participant DB as MySQL Database

    U1->>C1: Send message to U2
    C1->>API: POST /api/messages/send
    API->>NS: Process message
    NS->>DB: Store message
    DB-->>NS: Message stored
    NS->>WS: Broadcast to U2
    WS->>C2: Push notification
    C2-->>U2: Show notification

    U2->>C2: Open app to view message
    C2->>API: GET /api/messages/{user_id}
    API-->>C2: Return conversation
    C2-->>U2: Display message
```

## 📊 Error Handling Sequence

```mermaid
sequenceDiagram
    participant U as User
    participant C as Client App
    participant LB as Load Balancer
    participant API as API Gateway
    participant Controller as Controller
    participant Service as Service
    participant DB as MySQL Database

    U->>C: Perform action
    C->>LB: API Request
    LB->>API: Forward request
    API->>Controller: Route request

    alt Database Error
        Controller->>Service: Process request
        Service->>DB: Database operation
        DB-->>Service: Database error
        Service-->>Controller: Throw exception
        Controller-->>API: Return 500 error
        API-->>LB: 500 Internal Server Error
        LB-->>C: Error response
        C-->>U: Show error message
    else Validation Error
        Controller->>Controller: Validate input
        Controller-->>API: Return 422 error
        API-->>LB: 422 Validation Error
        LB-->>C: Error response
        C-->>U: Show validation errors
    else Authentication Error
        API->>API: Validate token
        API-->>LB: 401 Unauthorized
        LB-->>C: Error response
        C-->>U: Redirect to login
    end
```

## 🎯 Sequence Diagram Benefits

### **For Developers:**

1. **Clear Understanding**: Visual representation of system interactions
2. **Debugging**: Easy to trace data flow and identify issues
3. **Implementation**: Step-by-step guide for coding
4. **Testing**: Basis for integration test scenarios

### **For System Design:**

1. **Architecture Validation**: Verify component interactions
2. **Performance Analysis**: Identify bottlenecks in data flow
3. **Security Review**: Understand authentication and authorization flows
4. **Scalability Planning**: Identify areas for optimization

### **For Documentation:**

1. **Technical Reference**: Complete system behavior documentation
2. **Onboarding**: New developers can understand system quickly
3. **Stakeholder Communication**: Visual explanation of system processes
4. **Maintenance**: Reference for system modifications

## 🔧 Key Components in Sequences

### **Frontend Components:**

-   **Client App**: Mobile/Web application
-   **Load Balancer**: Request distribution
-   **API Gateway**: Request routing and authentication

### **Backend Components:**

-   **Controllers**: Request handling and response generation
-   **Services**: Business logic implementation
-   **Models**: Data access and manipulation
-   **Validation**: Input validation and sanitization

### **Data Storage:**

-   **MySQL Database**: Primary data storage
-   **Redis Cache**: Performance optimization
-   **File Storage**: Profile pictures and media

### **External Services:**

-   **Notification Service**: Push notifications and emails
-   **WebSocket Server**: Real-time communication
-   **Analytics Service**: User behavior tracking

---

**These sequence diagrams provide a comprehensive view of all major system interactions in the matrimonial API. They serve as a technical reference for understanding data flow, system behavior, and component communication.**
