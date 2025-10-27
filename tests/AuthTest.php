<?php
/**
 * Authentication Test Suite
 * Tests for AuthManager, User, and UserFactory classes
 */

require_once __DIR__ . '/../classes/Database.php';
require_once __DIR__ . '/../classes/AuthManager.php';
require_once __DIR__ . '/../classes/User.php';
require_once __DIR__ . '/../classes/UserFactory.php';

class AuthTest {
    private $auth;
    private $testUsername;
    private $testPassword = 'TestPass123';
    private $invalidPassword = 'wrongpass';
    
    public function __construct() {
        $this->testUsername = 'testuser_' . time();
        $this->auth = new AuthManager();
    }
    
    /**
     * Run all tests
     */
    public function runAllTests() {
        echo "=== Authentication Test Suite ===\n\n";
        
        $this->testUserFactoryCreation();
        $this->testUserGetters();
        $this->testRegistrationSuccess();
        $this->testRegistrationDuplicateUsername();
        $this->testLoginSuccess();
        $this->testLoginInvalidPassword();
        $this->testLoginNonexistentUser();
        $this->testSessionManagement();
        
        echo "\n=== All Tests Completed ===\n";
    }
    
    /**
     * Test: UserFactory creates User with correct data
     */
    private function testUserFactoryCreation() {
        echo "Test 1: UserFactory creates User correctly... ";
        try {
            $userData = [
                'username' => 'factorytest',
                'password' => $this->testPassword
            ];
            $user = UserFactory::create($userData);
            
            if ($user instanceof User) {
                echo "✓ PASS\n";
                return true;
            } else {
                echo "✗ FAIL: Not a User instance\n";
                return false;
            }
        } catch (Exception $e) {
            echo "✗ FAIL: " . $e->getMessage() . "\n";
            return false;
        }
    }
    
    /**
     * Test: User getters return correct values
     */
    private function testUserGetters() {
        echo "Test 2: User getters return correct values... ";
        try {
            $userData = [
                'username' => 'gettertest',
                'password' => $this->testPassword
            ];
            $user = UserFactory::create($userData);
            
            $username = $user->getUsername();
            $password = $user->getPassword();
            
            if ($username === 'gettertest' && !empty($password)) {
                echo "✓ PASS\n";
                return true;
            } else {
                echo "✗ FAIL: Getters returned incorrect values\n";
                return false;
            }
        } catch (Exception $e) {
            echo "✗ FAIL: " . $e->getMessage() . "\n";
            return false;
        }
    }
    
    /**
     * Test: Successful user registration
     */
    private function testRegistrationSuccess() {
        echo "Test 3: Successful user registration... ";
        try {
            $userData = [
                'username' => $this->testUsername,
                'password' => $this->testPassword
            ];
            
            $result = $this->auth->createUser($userData);
            
            if ($result === true) {
                echo "✓ PASS\n";
                return true;
            } else {
                echo "✗ FAIL: Registration returned false\n";
                return false;
            }
        } catch (Exception $e) {
            echo "✗ FAIL: " . $e->getMessage() . "\n";
            return false;
        }
    }
    
    /**
     * Test: Registration fails with duplicate username
     */
    private function testRegistrationDuplicateUsername() {
        echo "Test 4: Registration rejects duplicate username... ";
        try {
            $userData = [
                'username' => $this->testUsername,
                'password' => $this->testPassword
            ];
            
            $result = $this->auth->createUser($userData);
            
            if ($result === false) {
                echo "✓ PASS\n";
                return true;
            } else {
                echo "✗ FAIL: Should reject duplicate username\n";
                return false;
            }
        } catch (Exception $e) {
            echo "✗ FAIL: " . $e->getMessage() . "\n";
            return false;
        }
    }
    
    /**
     * Test: Successful login with correct credentials
     */
    private function testLoginSuccess() {
        echo "Test 5: Successful login with correct credentials... ";
        try {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $result = $this->auth->login($this->testUsername, $this->testPassword);
            
            if ($result === true && isset($_SESSION['user_id']) && isset($_SESSION['username'])) {
                echo "✓ PASS\n";
                return true;
            } else {
                echo "✗ FAIL: Login failed or session not set\n";
                return false;
            }
        } catch (Exception $e) {
            echo "✗ FAIL: " . $e->getMessage() . "\n";
            return false;
        }
    }
    
    /**
     * Test: Login fails with incorrect password
     */
    private function testLoginInvalidPassword() {
        echo "Test 6: Login rejects incorrect password... ";
        try {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION = array(); // Clear session
            $result = $this->auth->login($this->testUsername, $this->invalidPassword);
            
            if ($result === false && !isset($_SESSION['user_id'])) {
                echo "✓ PASS\n";
                return true;
            } else {
                echo "✗ FAIL: Should reject invalid password\n";
                return false;
            }
        } catch (Exception $e) {
            echo "✗ FAIL: " . $e->getMessage() . "\n";
            return false;
        }
    }
    
    /**
     * Test: Login fails with nonexistent user
     */
    private function testLoginNonexistentUser() {
        echo "Test 7: Login rejects nonexistent user... ";
        try {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION = array(); // Clear session
            $result = $this->auth->login('nonexistent_user_xyz', $this->testPassword);
            
            if ($result === false && !isset($_SESSION['user_id'])) {
                echo "✓ PASS\n";
                return true;
            } else {
                echo "✗ FAIL: Should reject nonexistent user\n";
                return false;
            }
        } catch (Exception $e) {
            echo "✗ FAIL: " . $e->getMessage() . "\n";
            return false;
        }
    }
    
    /**
     * Test: Session management (logout)
     */
    private function testSessionManagement() {
        echo "Test 8: Session management (logout)... ";
        try {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['user_id'] = 1;
            $_SESSION['username'] = 'testuser';
            
            $this->auth->logout();
            
            // After logout, session should be destroyed
            if (session_status() === PHP_SESSION_NONE) {
                echo "✓ PASS\n";
                return true;
            } else {
                echo "✗ FAIL: Session not properly destroyed\n";
                return false;
            }
        } catch (Exception $e) {
            echo "✗ FAIL: " . $e->getMessage() . "\n";
            return false;
        }
    }
}

// Run tests
if (php_sapi_name() === 'cli') {
    $test = new AuthTest();
    $test->runAllTests();
} else {
    echo "This test file should be run from command line: php tests/AuthTest.php\n";
}
?>
