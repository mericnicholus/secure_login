<?php
/**
 * Registration Endpoint - User Account Creation Handler
 * 
 * Handles POST requests for user registration. Accepts JSON input with username,
 * password, and password confirmation. Validates input and creates new user accounts.
 * Returns JSON response with registration status.
 */

// Configure error handling to prevent output corruption
error_reporting(E_ALL);
ini_set('display_errors', 0);
ob_start(); // Buffer output to catch any unexpected output

// Set response header to JSON for proper content type
header('Content-Type: application/json');

// Initialize default error response
$response = ['status' => 'error', 'message' => 'Invalid request'];

try {
    // Load required classes for user creation
    require_once 'classes/Database.php';
    require_once 'classes/AuthManager.php';

    // Parse JSON input from request body
    $data = json_decode(file_get_contents("php://input"), true);
    $username = trim($data["username"] ?? "");
    $password = $data["password"] ?? "";
    $confirm  = $data["confirmPassword"] ?? "";

    // ============================================
    // Step 1: Validate individual input fields
    // ============================================
    if (empty($username)) {
        $response = ["status" => "error", "message" => "Username is required."];
    } elseif (strlen($username) < 3) {
        // Ensure username meets minimum length requirement
        $response = ["status" => "error", "message" => "Username must be at least 3 characters long."];
    } elseif (empty($password)) {
        $response = ["status" => "error", "message" => "Password is required."];
    } elseif ($password !== $confirm) {
        // Verify password confirmation matches
        $response = ["status" => "error", "message" => "Passwords do not match."];
    } else {
        // ============================================
        // Step 2: Validate password strength
        // ============================================
        // Pattern requires: 8+ chars, uppercase, lowercase, and at least one number
        $pattern = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,}$/";
        
        if (!preg_match($pattern, $password)) {
            $response = [
                "status" => "error",
                "message" => "Password must be at least 8 characters long and include uppercase, lowercase, and a number."
            ];
        } else {
            // ============================================
            // Step 3: Create user account
            // ============================================
            $auth = new AuthManager();
            if ($auth->createUser([
                'username' => $username,
                'password' => $password
            ])) {
                // Registration successful
                $response = ["status" => "success", "message" => "Registration successful!"];
            } else {
                // Username already exists in database
                $response = ["status" => "error", "message" => "Username already exists."];
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

// Return JSON response to client
echo json_encode($response);
?>
