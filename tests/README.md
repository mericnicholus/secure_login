# Authentication Test Suite

## Overview
This test suite validates the authentication system including user registration, login, and session management.

## Test Cases

### 1. UserFactory Creation Test
- **Purpose**: Verify UserFactory creates User objects correctly
- **Input**: Valid username and password
- **Expected**: User instance returned
- **Anti-pattern Avoided**: Hard-coded test data (uses dynamic test username)

### 2. User Getters Test
- **Purpose**: Verify User class getters return correct values
- **Input**: User object created by factory
- **Expected**: Username and hashed password retrieved correctly
- **Anti-pattern Avoided**: Code duplication (reuses factory creation logic)

### 3. Registration Success Test
- **Purpose**: Verify successful user registration
- **Input**: New unique username and valid password
- **Expected**: Registration returns true
- **Anti-pattern Avoided**: Hard-coded credentials (uses time-based unique username)

### 4. Duplicate Username Test
- **Purpose**: Verify system rejects duplicate usernames
- **Input**: Already registered username
- **Expected**: Registration returns false
- **Anti-pattern Avoided**: Poor naming (clear test method name)

### 5. Login Success Test
- **Purpose**: Verify successful login with correct credentials
- **Input**: Registered username and correct password
- **Expected**: Login returns true, session variables set
- **Anti-pattern Avoided**: Hard-coded values (uses test credentials from setup)

### 6. Invalid Password Test
- **Purpose**: Verify login rejects incorrect password
- **Input**: Correct username, wrong password
- **Expected**: Login returns false, no session set
- **Anti-pattern Avoided**: Code duplication (reuses login logic)

### 7. Nonexistent User Test
- **Purpose**: Verify login rejects nonexistent users
- **Input**: Non-registered username
- **Expected**: Login returns false, no session set
- **Anti-pattern Avoided**: Hard-coded test data (uses clearly named test username)

### 8. Session Management Test
- **Purpose**: Verify logout properly destroys session
- **Input**: Active session
- **Expected**: Session variables cleared
- **Anti-pattern Avoided**: Code duplication (reuses session setup)

## Running Tests

### From Command Line
```bash
php tests/AuthTest.php
```

### Expected Output
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

## Design Principles Applied

### 1. Avoid Code Duplication
- Reusable test data constants (`$testUsername`, `$testPassword`)
- Shared setup in constructor
- Common exception handling pattern

### 2. Clear Naming Conventions
- Test method names clearly describe what is being tested
- Variable names are descriptive (e.g., `$invalidPassword`, `$testUsername`)
- No abbreviations that reduce clarity

### 3. No Hard-Coded Values
- Test username generated with timestamp to ensure uniqueness
- Test credentials stored as class properties
- Reusable across multiple test runs

### 4. Single Responsibility
- Each test method tests one specific behavior
- Clear pass/fail criteria
- Isolated test execution

## Test Coverage

| Component | Method | Coverage |
|-----------|--------|----------|
| UserFactory | create() | ✓ |
| User | __construct() | ✓ |
| User | getUsername() | ✓ |
| User | getPassword() | ✓ |
| AuthManager | createUser() | ✓ |
| AuthManager | login() | ✓ |
| AuthManager | logout() | ✓ |
