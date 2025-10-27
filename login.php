<?php
/**
 * Login Endpoint - User Authentication Handler
 * 
 * Handles POST requests for user login. Accepts JSON input with username and password,
 * validates credentials, and returns JSON response with authentication status.
 * Returns JSON to support AJAX requests from the frontend.
 */

// Configure error handling to prevent output corruption
error_reporting(E_ALL);
ini_set('display_errors', 0);
ob_start(); // Buffer output to catch any unexpected output

// Start session for user tracking
session_start();

// Set response header to JSON for proper content type
header('Content-Type: application/json');

// Initialize default error response
$response = ['status' => 'error', 'message' => 'Invalid request'];

try {
    // Load required classes for authentication
    require_once 'classes/Database.php';
    require_once 'classes/AuthManager.php';

    // Only process POST requests (login form submissions)
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        $input = json_decode(file_get_contents('php://input'), true);
        $username = $input['username'] ?? '';
        $password = $input['password'] ?? '';
        
        // Validate that both username and password are provided
        if (empty($username) || empty($password)) {
            $response = ['status' => 'error', 'message' => 'Username and password required'];
        } else {
            $auth = new AuthManager();
            if ($auth->login($username, $password)) {
               
                $response = ['status' => 'success', 'message' => 'Login successful'];
            } else {
                
                $response = ['status' => 'error', 'message' => 'Invalid credentials'];
            }
        }
    }
} catch (Throwable $e) {
    // Catch any exceptions and return error message
    $response = ['status' => 'error', 'message' => 'Server error: ' . $e->getMessage()];
}
// Check for any unexpected output that would corrupt JSON response
$output = ob_get_clean();
if (!empty($output)) {
    $response = ['status' => 'error', 'message' => 'Unexpected output: ' . substr($output, 0, 100)];
}

echo json_encode($response);
?>
