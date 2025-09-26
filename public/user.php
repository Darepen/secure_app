<?php
// secure_app/public/user.php

/**
 * User Actions Controller
 *
 * This file handles actions initiated by an authenticated user, such as
 * deleting their own account. It ensures the user is logged in and the
 * request is legitimate before performing any sensitive actions.
 */

// 1. INITIALIZATION
// -----------------------------------------------------------------------------
require_once __DIR__ . '/../config/db_config.php';
require_once __DIR__ . '/../src/lib/auth.php';
require_once __DIR__ . '/../src/lib/security.php';


// 2. SECURITY: AUTHENTICATION CHECK
// -----------------------------------------------------------------------------
// These actions are for authenticated users only. If not logged in, redirect.
if (!is_logged_in()) {
    header('Location: index.php?view=login');
    exit;
}


// 3. ACTION ROUTING
// -----------------------------------------------------------------------------
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'delete':
        handle_account_deletion();
        break;
        
    default:
        // If no valid action, redirect to the user's dashboard.
        http_response_code(400); // Bad Request
        header('Location: index.php?view=welcome');
        exit;
}


// 4. ACTION HANDLER FUNCTIONS
// -----------------------------------------------------------------------------

/**
 * Handles the permanent deletion of the user's own account.
 */
function handle_account_deletion() {
    // This action must be a POST request.
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405); // Method Not Allowed
        exit('Invalid request method.');
    }

    // SECURITY: Validate the CSRF token. This is critical.
    if (!validate_csrf_token()) {
        $_SESSION['error_message'] = 'Invalid or expired form submission. Please try again.';
        http_response_code(403); // Forbidden
        header('Location: index.php?view=delete_account');
        exit;
    }

    // Get the user's ID from their secure session.
    // We NEVER trust a user ID submitted from a form for this kind of action.
    $user_id_to_delete = $_SESSION['user_id'];

    // Call the library function to perform the deletion.
    global $mysqli;
    $deleted = delete_user_account($mysqli, $user_id_to_delete);

    if ($deleted) {
        // Deletion was successful. Now, completely destroy the session.
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();

        // Redirect to the login page with a success message.
        // We start a new temporary session just to pass this one message.
        session_start();
        $_SESSION['success_message'] = 'Your account has been permanently deleted.';
        header('Location: index.php?view=login');
        exit;
    } else {
        // Deletion failed for some reason (e.g., database error).
        $_SESSION['error_message'] = 'An unexpected error occurred while deleting your account. Please try again.';
        header('Location: index.php?view=delete_account');
        exit;
    }
}
?>