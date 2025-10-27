<?php
/**
 * User Class - Data Model for User Entity
 * 
 * Represents a user in the system with encapsulated properties.
 * Provides getters and setters for controlled access to user data.
 * Password is expected to be hashed before storage.
 */
class User {
    /** @var int|null The unique user identifier from database */
    private $id;
    
    /** @var string The username for login */
    private $username;
    
    /** @var string The hashed password for authentication */
    private $password;

    /**
     * Constructor - Initialize User object
     * 
     * Creates a new User instance with username and password.
     * Password should be hashed before passing to this constructor.
     * 
     * @param string $username The user's login username
     * @param string $password The user's hashed password
     */
    public function __construct($username, $password) {
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * Get user ID
     * 
     * @return int|null The user's unique identifier
     */
    public function getId() { 
        return $this->id; 
    }
    
    /**
     * Get username
     * 
     * @return string The user's login username
     */
    public function getUsername() { 
        return $this->username; 
    }
    
    /**
     * Get password hash
     * 
     * @return string The user's hashed password
     */
    public function getPassword() { 
        return $this->password; 
    }

    /**
     * Set user ID
     * 
     * @param int $id The user's unique identifier from database
     */
    public function setId($id) { 
        $this->id = $id; 
    }
}