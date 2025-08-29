# 🔐 Account Management API - Matrimonial Application

## 📋 **Overview**

This document outlines the new account management features including Remember Login, User Blocking, and Account Deletion functionality.

## 🚀 **Features Implemented**

### 1. **Remember Login System**
- Extended token expiration for "Remember Me" functionality
- Login history tracking with IP and user agent
- Configurable remember login preference

### 2. **User Blocking System**
- Block/unblock users with reasons
- Prevent interactions between blocked users
- Block statistics and management

### 3. **Account Deletion System**
- Soft delete with recovery option
- Password verification for deletion
- Deletion reason tracking

## 🔗 **API Endpoints**

### **Authentication & Account Management**

#### **1. Enhanced Login with Remember Me**
```http
POST /api/login
```

**Request Body:**
```json
{
    "email": "user@example.com",
    "password": "password123",
    "remember_login": true
}
```

**Response:**
```json
{
    "message": "Login successful",
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "user@example.com",
        "remember_login": true,
        "last_login_at": "2025-08-29T13:15:00.000000Z",
        "last_login_ip": "192.168.1.1",
        "last_login_user_agent": "Mozilla/5.0..."
    },
    "token": "1|abc123...",
    "expires_at": "2026-08-29T13:15:00.000000Z",
    "remember_login": true
}
```

#### **2. Update Remember Login Preference**
```http
PUT /api/account/remember-login
```

**Request Body:**
```json
{
    "remember_login": true
}
```

**Response:**
```json
{
    "message": "Remember login preference updated successfully",
    "remember_login": true
}
```

#### **3. Get Login History**
```http
GET /api/account/login-history
```

**Response:**
```json
{
    "last_login_at": "2025-08-29T13:15:00.000000Z",
    "last_login_ip": "192.168.1.1",
    "last_login_user_agent": "Mozilla/5.0...",
    "remember_login": true
}
```

#### **4. Delete Account**
```http
DELETE /api/account
```

**Request Body:**
```json
{
    "password": "password123",
    "reason": "No longer interested in the service"
}
```

**Response:**
```json
{
    "message": "Account deleted successfully. You can restore it within 30 days by contacting support."
}
```

### **User Blocking System**

#### **1. Block a User**
```http
POST /api/blocks/{user_id}
```

**Request Body:**
```json
{
    "reason": "Inappropriate behavior"
}
```

**Response:**
```json
{
    "message": "User blocked successfully.",
    "blocked_user": {
        "id": 2,
        "name": "Jane Smith",
        "email": "jane@example.com"
    },
    "reason": "Inappropriate behavior"
}
```

#### **2. Unblock a User**
```http
DELETE /api/blocks/{user_id}
```

**Response:**
```json
{
    "message": "User unblocked successfully.",
    "unblocked_user": {
        "id": 2,
        "name": "Jane Smith",
        "email": "jane@example.com"
    }
}
```

#### **3. Get Blocked Users List**
```http
GET /api/blocks?page=1
```

**Response:**
```json
{
    "blocked_users": {
        "data": [
            {
                "id": 2,
                "name": "Jane Smith",
                "email": "jane@example.com",
                "pivot": {
                    "reason": "Inappropriate behavior",
                    "blocked_at": "2025-08-29T13:15:00.000000Z"
                }
            }
        ],
        "current_page": 1,
        "per_page": 20,
        "total": 1
    }
}
```

#### **4. Get Users Who Blocked You**
```http
GET /api/blocks/blocked-by?page=1
```

**Response:**
```json
{
    "blocked_by_users": {
        "data": [
            {
                "id": 3,
                "name": "Bob Johnson",
                "email": "bob@example.com",
                "pivot": {
                    "reason": "Not interested",
                    "blocked_at": "2025-08-29T13:15:00.000000Z"
                }
            }
        ],
        "current_page": 1,
        "per_page": 20,
        "total": 1
    }
}
```

#### **5. Check Block Status**
```http
GET /api/blocks/{user_id}/status
```

**Response:**
```json
{
    "user_id": 2,
    "is_blocked": false,
    "is_blocked_by": true,
    "can_interact": false
}
```

#### **6. Get Block Statistics**
```http
GET /api/blocks/stats
```

**Response:**
```json
{
    "blocked_count": 1,
    "blocked_by_count": 1
}
```

## 🗄️ **Database Schema**

### **Users Table (Updated)**
```sql
-- New columns added to users table
ALTER TABLE users ADD COLUMN remember_login BOOLEAN DEFAULT FALSE;
ALTER TABLE users ADD COLUMN last_login_at TIMESTAMP NULL;
ALTER TABLE users ADD COLUMN last_login_ip VARCHAR(45) NULL;
ALTER TABLE users ADD COLUMN last_login_user_agent TEXT NULL;
ALTER TABLE users ADD COLUMN status ENUM('active', 'suspended', 'deleted') DEFAULT 'active';
ALTER TABLE users ADD COLUMN deletion_reason TEXT NULL;
-- deleted_at already exists for soft deletes
```

### **User Blocks Table**
```sql
CREATE TABLE user_blocks (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    blocker_id BIGINT UNSIGNED NOT NULL,
    blocked_user_id BIGINT UNSIGNED NOT NULL,
    reason TEXT NULL,
    blocked_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    FOREIGN KEY (blocker_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (blocked_user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_block (blocker_id, blocked_user_id),
    INDEX idx_blocker (blocker_id),
    INDEX idx_blocked (blocked_user_id)
);
```

## 🔒 **Security Features**

### **Token Management**
- **Regular Login**: Token expires in 24 hours
- **Remember Login**: Token expires in 1 year
- **Automatic cleanup**: Expired tokens are automatically removed

### **Account Protection**
- **Password verification**: Required for account deletion
- **Soft deletion**: Accounts can be recovered within 30 days
- **Status tracking**: Active, suspended, or deleted states

### **Blocking Protection**
- **Bidirectional blocking**: Prevents all interactions
- **Discover exclusion**: Blocked users don't appear in recommendations
- **Like prevention**: Cannot like blocked users
- **Message prevention**: Cannot message blocked users

## 📱 **Frontend Integration Examples**

### **React Native/Expo Example**

#### **Login with Remember Me**
```javascript
const login = async (email, password, rememberLogin) => {
  try {
    const response = await fetch('/api/login', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        email,
        password,
        remember_login: rememberLogin
      })
    });
    
    const data = await response.json();
    
    // Store token with appropriate expiration
    await AsyncStorage.setItem('auth_token', data.token);
    await AsyncStorage.setItem('token_expires_at', data.expires_at);
    await AsyncStorage.setItem('remember_login', data.remember_login);
    
    return data;
  } catch (error) {
    console.error('Login error:', error);
    throw error;
  }
};
```

#### **Block a User**
```javascript
const blockUser = async (userId, reason) => {
  try {
    const token = await AsyncStorage.getItem('auth_token');
    const response = await fetch(`/api/blocks/${userId}`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${token}`
      },
      body: JSON.stringify({ reason })
    });
    
    return await response.json();
  } catch (error) {
    console.error('Block user error:', error);
    throw error;
  }
};
```

#### **Delete Account**
```javascript
const deleteAccount = async (password, reason) => {
  try {
    const token = await AsyncStorage.getItem('auth_token');
    const response = await fetch('/api/account', {
      method: 'DELETE',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${token}`
      },
      body: JSON.stringify({ password, reason })
    });
    
    if (response.ok) {
      // Clear all stored data
      await AsyncStorage.clear();
      // Navigate to login screen
      navigation.navigate('Login');
    }
    
    return await response.json();
  } catch (error) {
    console.error('Delete account error:', error);
    throw error;
  }
};
```

## 🧪 **Testing Examples**

### **cURL Commands**

#### **Login with Remember Me**
```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "john@example.com",
    "password": "password123",
    "remember_login": true
  }'
```

#### **Block a User**
```bash
curl -X POST http://localhost:8000/api/blocks/2 \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -d '{
    "reason": "Inappropriate behavior"
  }'
```

#### **Delete Account**
```bash
curl -X DELETE http://localhost:8000/api/account \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -d '{
    "password": "password123",
    "reason": "No longer interested"
  }'
```

## 🔄 **Business Logic**

### **Remember Login Flow**
1. User logs in with `remember_login: true`
2. Token created with 1-year expiration
3. User preference updated in database
4. Login history tracked (IP, user agent, timestamp)

### **Blocking Flow**
1. User blocks another user with optional reason
2. Block record created in `user_blocks` table
3. Blocked user excluded from discover recommendations
4. All interactions prevented between blocked users

### **Account Deletion Flow**
1. User requests account deletion with password verification
2. Account status changed to 'deleted'
3. Deletion reason stored
4. All tokens invalidated
5. Account soft deleted (can be restored within 30 days)

## 📊 **Error Handling**

### **Common Error Responses**

#### **Invalid Password for Deletion**
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "password": ["The provided password is incorrect."]
    }
}
```

#### **Already Blocked User**
```json
{
    "message": "User is already blocked."
}
```

#### **Cannot Block Self**
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "user": ["You cannot block yourself."]
    }
}
```

#### **Account Not Active**
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "email": ["Your account is not active. Please contact support."]
    }
}
```

## 🚀 **Deployment Notes**

1. **Database Migration**: Run migrations to create new tables and columns
2. **Token Cleanup**: Consider setting up a scheduled job to clean expired tokens
3. **Monitoring**: Monitor block statistics and account deletion rates
4. **Backup**: Ensure regular backups for account recovery purposes

## 📈 **Future Enhancements**

1. **Block Categories**: Different types of blocks (spam, harassment, etc.)
2. **Temporary Blocks**: Time-limited blocks with automatic expiration
3. **Block Appeals**: Process for users to appeal blocks
4. **Advanced Analytics**: Detailed blocking and account management analytics
5. **Admin Panel**: Administrative interface for managing blocks and deleted accounts

---

This implementation provides a comprehensive account management system with security, user control, and data protection features essential for a matrimonial application.
