<?php
// secure_app/public/auth.php
/**
 * Authentication Controller
 *
 * This file handles all authentication-related actions:
 * - Processing registration form submissions.
 * - Processing login form submissions.
 * - Handling user logout.
 * It does not render any HTML itself; it only processes data and redirects.
 */

// 1. INITIALIZATION
// -----------------------------------------------------------------------------
require_once __DIR__ . '/../config/db_config.php';
require_once __DIR__ . '/../src/lib/security.php';
require_once __DIR__ . '/../src/lib/auth.php';

// 2. ACTION ROUTING
// -----------------------------------------------------------------------------
// Determine the action from the GET parameter.
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'register':
        handle_registration();
        break;
    
    case 'login':
        handle_login();
        break;
        
    case 'logout':
        handle_logout();
        break;
        
    default:
        // If no valid action is specified, redirect to the homepage.
        http_response_code(400); // Bad Request
        header('Location: index.php');
        exit;
}


// 3. ACTION HANDLER FUNCTIONS
// -----------------------------------------------------------------------------

/**
 * Handles the registration form submission.
 */
function handle_registration() {
    // This action must be a POST request.
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405); // Method Not Allowed
        exit('Invalid request method.');
    }

    // SECURITY: Validate the CSRF token first.
    if (!validate_csrf_token()) {
        $_SESSION['error_message'] = 'Invalid or expired form submission. Please try again.';
        http_response_code(403); // Forbidden
        header('Location: index.php?view=register');
        exit;
    }

    // Sanitize and validate inputs.
    $fullName = trim($_POST['fullName'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';
    $agreePrivacy = isset($_POST['agreePrivacy']);

    // --- Strict Validation Rules ---
    if (empty($fullName) || strlen($fullName) > 100) {
        $error = 'Full name is required and must be less than 100 characters.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'A valid email address is required.';
    } elseif (strlen($password) < 12) {
        $error = 'Password must be at least 12 characters long.';
    } elseif ($password !== $password_confirm) {
        $error = 'Passwords do not match.';
    } elseif (!$agreePrivacy) {
        $error = 'You must agree to the Privacy Policy.';
    }

    if (isset($error)) {
        $_SESSION['error_message'] = $error;
        // Store submitted data to repopulate form for better UX
        $_SESSION['submitted_data'] = ['fullName' => $fullName, 'email' => $email];
        header('Location: index.php?view=register');
        exit;
    }
    
    // If validation passes, attempt to register the user.
    global $mysqli; // Get the database connection from db_config.php
    $result = register_user($mysqli, $fullName, $email, $password);

    if ($result['success']) {
        $_SESSION['success_message'] = $result['message'];
        header('Location: index.php?view=register');
    } else {
        $_SESSION['error_message'] = $result['message'];
        $_SESSION['submitted_data'] = ['fullName' => $fullName, 'email' => $email];
        header('Location: index.php?view=register');
    }
    exit;
}

/**
 * Handles the login form submission.
 */
function handle_login() {
    // This action must be a POST request.
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        exit('Invalid request method.');
    }

    // SECURITY: Validate the CSRF token.
    if (!validate_csrf_token()) {
        $_SESSION['error_message'] = 'Invalid or expired form submission. Please try again.';
        http_response_code(403);
        header('Location: index.php?view=login');
        exit;
    }

    // Get email and password from the form.
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $_SESSION['error_message'] = 'Email and password are required.';
        header('Location: index.php?view=login');
        exit;
    }
    
    // Attempt to log the user in.
    global $mysqli;
    $result = login_user($mysqli, $email, $password);

    if ($result['success']) {
        // Redirect to the protected dashboard on successful login.
        header('Location: index.php?view=welcome');
    } else {
        $_SESSION['error_message'] = $result['message'];
        // Store submitted email to repopulate form for better UX
        $_SESSION['submitted_email'] = $email;
        header('Location: index.php?view=login');
    }
    exit;
}

/**
 * Handles the user logout action.
 */
function handle_logout() {
    // Unset all of the session variables.
    $_SESSION = [];

    // Delete the session cookie.
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    // Finally, destroy the session.
    session_destroy();

    // Redirect to the homepage.
    header('Location: index.php');
    exit;
}
?>