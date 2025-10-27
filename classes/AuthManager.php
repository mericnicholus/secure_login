<?php
require_once __DIR__ . '/User.php';
require_once __DIR__ . '/UserFactory.php';

/**
 * AuthManager Class - Authentication Logic Handler
 * 
 * Handles all authentication operations including user registration, login,
 * and logout. Uses the Factory pattern for user creation and implements
 * secure password hashing with bcrypt.
 */
class AuthManager {
    /** @var PDO The database connection object */
    private $db;

    /**
     * Constructor - Initialize database connection
     * 
     * Retrieves the singleton database instance and stores the connection
     * for use in authentication operations.
     */
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Create a new user account (Registration)
     * 
     * Validates that the username doesn't already exist, creates a User object
     * through the Factory pattern, and inserts it into the database with a
     * securely hashed password.
     * 
     * @param array $userData Array containing 'username' and 'password' keys
     * @return bool True if registration successful, false if username exists
     * @throws Exception If database operation fails
     */
    public function createUser($userData) {
        try {
            // Check if username already exists to prevent duplicates
            $stmt = $this->db->prepare("SELECT id FROM users WHERE username = ?");
            $stmt->execute([$userData['username']]);
            if ($stmt->fetch()) {
                return false; // Username already exists
            }
            
            // Create User object with validation and password hashing via Factory
            $user = UserFactory::create($userData);
            
            // Insert new user into database with hashed password
            $stmt = $this->db->prepare("INSERT INTO users (username, password_hash) VALUES (?, ?)");
            $stmt->execute([
                $user->getUsername(),
                $user->getPassword()
            ]);

            return true;
        } catch (Exception $e) {
            throw new Exception("Registration error: " . $e->getMessage());
        }
    }

    /**
     * Authenticate user login
     * 
     * Retrieves user from database and verifies password using timing-safe
     * comparison. On successful authentication, sets session variables for
     * user tracking.
     * 
     * @param string $username The username to authenticate
     * @param string $password The plain-text password to verify
     * @return bool True if authentication successful, false otherwise
     * @throws Exception If database operation fails
     */
    public function login($username, $password) {
        try {
            // Retrieve user record from database by username
            $stmt = $this->db->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verify password using timing-safe comparison and set session on success
            if ($user && password_verify($password, $user['password_hash'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                return true;
            }
            return false;
        } catch (Exception $e) {
            throw new Exception("Login error: " . $e->getMessage());
        }
    }

    public function logout() {
        session_destroy();
    }
}