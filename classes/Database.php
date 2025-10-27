<?php
/**
 * Database Class - Singleton Pattern Implementation
 * 
 * Manages a single database connection throughout the application lifecycle.
 * Ensures only one PDO connection exists, preventing connection overhead and
 * maintaining consistent database state across the entire application.
 */
class Database {

    private static $instance = null;
    
    private $connection;
    
    /**
     * Private constructor - Singleton pattern
     * 
     * Prevents direct instantiation. Connection is established here using
     * credentials from config.php. Throws exception if connection fails.
     * 
     * @throws Exception If database connection fails
     */
    private function __construct() {
        // Load database configuration from config.php
        $config = require_once __DIR__ . '/../config.php';
        
        try {
            // Create PDO connection with error mode set to exceptions
            $this->connection = new PDO(
                "mysql:host={$config['host']};dbname={$config['database']}",
                $config['username'],
                $config['password']
            );
            
            // Set error mode to throw exceptions for better error handling
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            throw new Exception("Connection failed: " . $e->getMessage());
        }
    }

    /**
     * Get singleton instance
     * 
     * Returns the single instance of the Database class. Creates the instance
     * on first call, then returns the same instance for all subsequent calls.
     * This ensures only one database connection exists throughout the application.
     * 
     * @return Database The singleton instance
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Get the PDO connection object
     * 
     * Returns the active database connection for executing queries.
     * 
     * @return PDO The PDO database connection
     */
    public function getConnection() {
        return $this->connection;
    }
}