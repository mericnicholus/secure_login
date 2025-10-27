<?php
/**
 * UserFactory Class - Factory Pattern Implementation
 */
class UserFactory {
    /**
     * Create a new User instance with validation and password hashing
     * 
     * @param array $userData Array containing 'username' and 'password' keys
     * @return User A new User instance with hashed password
     * @throws Exception If required user data is missing
     */
    public static function create($userData) {
        // Validate that both username and password are provided
        if (!isset($userData['username']) || !isset($userData['password'])) {
            throw new Exception('Missing required user data');
        }

        // Create and return new User instance with securely hashed password
        // PASSWORD_DEFAULT uses bcrypt and automatically handles salt generation
        return new User(
            $userData['username'],
            password_hash($userData['password'], PASSWORD_DEFAULT)
        );
    }
}