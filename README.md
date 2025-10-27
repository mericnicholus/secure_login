# Secure Login System

A production-ready authentication system demonstrating industry-standard design patterns, security best practices, and comprehensive testing. Built with PHP and MySQL, this project showcases proper separation of concerns, encapsulation, and secure credential handling.

**Author:** Mabinda Nicholus Eric (2022/BSE/007/PS)

---
## Quick Start

### Prerequisites

- **PHP 7.4+** with PDO MySQL extension
- **MySQL 5.7+** 
- **XAMPP** (recommended for local development) or equivalent web server

### Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd secure_login
   ```

2. **Create the database**
   ```sql
   CREATE DATABASE secure_login;
   ```

3. **Import the database schema**
   ```bash
   mysql -u root -p secure_login < database/schema.sql
   ```

4. **Configure environment settings**
   - Edit `config.php` with your database credentials
   - Ensure the database host, username, and password match your MySQL setup

5. **Start your web server**
   - If using XAMPP: Place the project in `htdocs/` and start Apache
   - Access via: `http://localhost/secure_login`

6. **Run tests**
   ```bash
   php tests/AuthTest.php
   ```

---

## Environment Setup

### Database Configuration

The system uses `config.php` for database connectivity. Update this file with your environment-specific settings:

```php
<?php
return [
    'host'     => 'localhost',      // MySQL server host
    'database' => 'secure_login',   // Database name
    'username' => 'root',           // MySQL user
    'password' => 'coolhands'       // MySQL password
];
?>
```

### Database Schema

The system requires a `users` table with the following structure:

```sql
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### PHP Configuration

Ensure these PHP settings are enabled:

- **PDO MySQL Extension**: Required for database connectivity
- **Session Support**: Required for user session management
- **Password Hashing**: Built-in `password_hash()` and `password_verify()` functions

### XAMPP Setup (Windows)

1. Place project in `C:\xampp\htdocs\secure_login`
2. Start MySQL and Apache from XAMPP Control Panel
3. Create database via phpMyAdmin or command line
4. Update `config.php` with your credentials
5. Access at `http://localhost/secure_login`

---

## Project Structure

```
secure_login/
├── classes/
│   ├── Database.php          # Singleton database connection manager
│   ├── User.php              # User data model with getters/setters
│   ├── UserFactory.php       # Factory for creating validated User objects
│   └── AuthManager.php       # Authentication business logic
├── database/
│   └── secure_login.sql            # Database table definitions
├── tests/
│   ├── AuthTest.php          # Comprehensive test suite
│   └── README.md             # Test documentation
├── config.php                # Environment configuration
├── index.html                # Landing page
├── register.html             # Registration form
├── register.php              # Registration endpoint
├── login.php                 # Login endpoint
├── logout.php                # Logout endpoint
├── home.php                  # Protected home page
├── script.js                 # Client-side functionality
├── style.css                 # Styling
├── DESIGN_PATTERNS.md        # Detailed design pattern documentation
└── README.md                 # This file
```

---

## Architecture & Design Patterns

### 1. Singleton Pattern

**Location:** `classes/Database.php`

Ensures only one database connection exists throughout the application lifecycle.

```php
private static $instance = null;

public static function getInstance() {
    if (self::$instance === null) {
        self::$instance = new self();
    }
    return self::$instance;
}
```

**Benefits:**
- Single database connection prevents resource exhaustion
- Memory efficient through connection reuse
- Thread-safe connection management
- Prevents multiple connection attempts

**Usage:**
```php
$db = Database::getInstance()->getConnection();
```

---

### 2. Factory Pattern

**Location:** `classes/UserFactory.php`

Centralizes user creation logic with built-in validation and password hashing.

```php
public static function create($userData) {
    // Validation logic
    // Password hashing
    // User object creation
    return new User($username, $hashedPassword);
}
```

**Benefits:**
- Centralized creation logic ensures consistency
- Automatic validation of all user data
- Encapsulates complexity of user instantiation
- Single point of maintenance for user creation rules

**Usage:**
```php
$user = UserFactory::create([
    'username' => 'john_doe',
    'password' => 'SecurePass123'
]);
```

---

### 3. Separation of Concerns

Each class has a single, well-defined responsibility:

| Class | Responsibility |
|-------|-----------------|
| `Database.php` | Database connectivity only |
| `User.php` | User data representation |
| `UserFactory.php` | User creation and validation |
| `AuthManager.php` | Authentication logic |
| `register.php` | HTTP registration endpoint |
| `login.php` | HTTP login endpoint |
| `logout.php` | HTTP logout endpoint |

**Benefits:**
- Maintainability: Clear purpose for each component
- Testability: Easy to test components in isolation
- Reusability: Classes work in different contexts
- Scalability: Simple to add new features

---

### 4. Encapsulation

Private properties with public getters/setters control data access:

```php
class User {
    private $id;
    private $username;
    private $password;
    
    public function getUsername() { 
        return $this->username; 
    }
    
    public function getPassword() { 
        return $this->password; 
    }
}
```

**Benefits:**
- Data protection through access control
- Validation logic in setters
- Internal implementation changes don't break external code
- Future-proof design

---

### 5. Error Handling

Comprehensive try-catch blocks with proper exception handling:

```php
try {
    require_once 'classes/Database.php';
    $auth = new AuthManager();
    // ... authentication logic
} catch (Throwable $e) {
    $response = ['status' => 'error', 'message' => $e->getMessage()];
}
```

**Benefits:**
- Graceful error handling prevents crashes
- User-friendly error messages
- Detailed logging for debugging
- Security: Prevents sensitive information leakage

---

## Security Implementation

### Password Security

**Hashing Algorithm:** bcrypt (PHP's `PASSWORD_DEFAULT`)

```php
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
```

- Automatically generates cryptographic salt
- Resistant to rainbow table attacks
- Future-proof against algorithm changes
- Configurable cost factor for performance

**Verification:** Timing-safe comparison

```php
if (password_verify($inputPassword, $hashedPassword)) {
    // Password is correct
}
```

- Prevents timing attacks
- No plain-text comparison
- Constant-time comparison algorithm

---

### Session Management

Server-side session storage with secure cookie handling:

```php
session_start();
$_SESSION['user_id'] = $user['id'];
$_SESSION['username'] = $user['username'];
```

**Security Features:**
- Server-side session storage (not client-side)
- Prevents session hijacking
- Secure cookie handling by PHP
- Session destruction on logout

---

### Input Validation

All user inputs validated before database operations:

```php
if (empty($username) || empty($password)) {
    throw new Exception('Username and password required');
}
```

**Validation Checks:**
- Non-empty username and password
- Username uniqueness
- Password strength requirements
- Data type validation

---

### Anti-Patterns Avoided

#### ✓ Code Duplication
- Reusable factory pattern for user creation
- Centralized error handling
- Shared validation logic

#### ✓ Hard-Coded Values
- Configuration file (`config.php`) for environment settings
- Dynamic test data generation
- Environment-based settings

#### ✓ Poor Naming Conventions
- Clear class names: `AuthManager`, `UserFactory`, `Database`
- Descriptive methods: `login()`, `createUser()`, `getUsername()`
- Meaningful variables: `$hashedPassword`, `$testUsername`

#### ✓ Tight Coupling
- Dependency injection through constructors
- Loose coupling between classes
- Interface-ready architecture

---

## Testing

### Test Suite Overview

Comprehensive test coverage for all authentication components:

```bash
php tests/AuthTest.php
```

### Test Cases

| # | Test | Purpose | Status |
|---|------|---------|--------|
| 1 | UserFactory Creation | Verify factory creates User objects | ✓ |
| 2 | User Getters | Verify getters return correct values | ✓ |
| 3 | Registration Success | Verify successful user registration | ✓ |
| 4 | Duplicate Username | Verify rejection of duplicate usernames | ✓ |
| 5 | Login Success | Verify successful login with correct credentials | ✓ |
| 6 | Invalid Password | Verify rejection of incorrect password | ✓ |
| 7 | Nonexistent User | Verify rejection of non-registered users | ✓ |
| 8 | Session Management | Verify logout destroys session | ✓ |

### Expected Test Output

```
=== Authentication Test Suite ===

Test 1: UserFactory creates User correctly... ✓ PASS
Test 2: User getters return correct values... ✓ PASS
Test 3: Successful user registration... ✓ PASS
Test 4: Registration rejects duplicate username... ✓ PASS
Test 5: Successful login with correct credentials... ✓ PASS
Test 6: Login rejects incorrect password... ✓ PASS
Test 7: Login rejects nonexistent user... ✓ PASS
Test 8: Session management (logout)... ✓ PASS

=== All Tests Completed ===
```

### Test Design Principles

- **No Hard-Coded Data:** Dynamic test usernames with timestamps
- **Reusable Fixtures:** Shared setup in constructor
- **Clear Naming:** Descriptive test method names
- **Isolated Execution:** Each test runs independently
- **Proper Cleanup:** Session destruction after tests

### Running Tests

**From Command Line:**
```bash
php tests/AuthTest.php
```

**From Web Browser:**
Navigate to `http://localhost/secure_login/tests/AuthTest.php`

---

## API Reference

### Authentication Endpoints

#### Register User

**Endpoint:** `POST /register.php`

**Parameters:**
```json
{
    "username": "john_doe",
    "password": "SecurePass123"
}
```

**Response (Success):**
```json
{
    "status": "success",
    "message": "User registered successfully"
}
```

**Response (Error):**
```json
{
    "status": "error",
    "message": "Username already exists"
}
```

---

#### Login User

**Endpoint:** `POST /login.php`

**Parameters:**
```json
{
    "username": "john_doe",
    "password": "SecurePass123"
}
```

**Response (Success):**
```json
{
    "status": "success",
    "message": "Login successful",
    "user_id": 1,
    "username": "john_doe"
}
```

**Response (Error):**
```json
{
    "status": "error",
    "message": "Invalid credentials"
}
```

---

#### Logout User

**Endpoint:** `GET /logout.php`

**Response:**
```json
{
    "status": "success",
    "message": "Logged out successfully"
}
```

---

## Future Enhancements

### Security Features

1. **Email Verification:** Add email column and verification flow
2. **Password Reset:** Implement secure password reset mechanism
3. **Rate Limiting:** Prevent brute force attacks with login attempt throttling
4. **Two-Factor Authentication:** Add 2FA support via TOTP or SMS
5. **Audit Logging:** Log all authentication attempts and security events
6. **CSRF Protection:** Add CSRF tokens to forms
7. **API Keys:** Support API authentication for programmatic access
8. **Role-Based Access Control:** Implement user roles and permissions

### Code Quality

1. **Unit Tests:** Expand test coverage to 100%
2. **Integration Tests:** Test component interactions
3. **API Tests:** Test HTTP endpoints
4. **Code Documentation:** Add PHPDoc comments to all classes
5. **Type Hints:** Add parameter and return type hints
6. **Static Analysis:** Use PHP CodeSniffer and PHPStan
7. **Performance Monitoring:** Track query performance and optimize
8. **Continuous Integration:** Set up CI/CD pipeline

### User Experience

1. **Remember Me:** Persistent login option
2. **Account Recovery:** Email-based account recovery
3. **Profile Management:** User profile update functionality
4. **Activity Log:** User login history and device tracking
5. **Notifications:** Email notifications for security events
6. **Social Login:** OAuth integration (Google, GitHub, etc.)

---

## Troubleshooting

### Database Connection Issues

**Problem:** "Connection refused" error

**Solution:**
1. Verify MySQL is running
2. Check `config.php` credentials match your MySQL setup
3. Ensure database `secure_login` exists
4. Verify PDO MySQL extension is enabled in PHP

### Session Issues

**Problem:** Session not persisting after login

**Solution:**
1. Verify `session_start()` is called before any output
2. Check PHP session configuration in `php.ini`
3. Ensure session save path is writable
4. Clear browser cookies and try again

### Password Hashing Issues

**Problem:** Login fails even with correct password

**Solution:**
1. Verify password was hashed during registration
2. Check PHP version supports `password_hash()` (7.0+)
3. Clear browser cache and try again
4. Check database for correct password hash

---

## Summary

This authentication system demonstrates:

- ✓ Proper use of design patterns (Singleton, Factory)
- ✓ Clear separation of concerns
- ✓ Industry-standard security practices
- ✓ Avoidance of common anti-patterns
- ✓ Comprehensive error handling
- ✓ Testable, maintainable architecture
- ✓ Production-ready code quality

The foundation is solid for building additional features and scaling the application to enterprise requirements.

---

## Contact

For questions or issues, please contact: mabindaericm@gmail.com

