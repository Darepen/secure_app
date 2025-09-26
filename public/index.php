<?php
// secure_app/public/index.php

/**
 * Main Application Router / Controller
 *
 * This is the primary entry point for the application.
 * It handles routing requests to the appropriate views based on user session status
 * and GET parameters. All core logic is offloaded to the libraries in the `src` directory.
 */

// 1. INITIALIZATION
// -----------------------------------------------------------------------------
// Require the core configuration file which initializes the database and session.
// Using __DIR__ ensures the path is always correct, regardless of how the script is called.
require_once __DIR__ . '/../config/db_config.php';

// Require the authentication and security libraries.
require_once __DIR__ . '/../src/lib/auth.php';
require_once __DIR__ . '/../src/lib/security.php';


// 2. ROUTING LOGIC
// -----------------------------------------------------------------------------
// Determine which view to display. The default view depends on login status.
$view = $_GET['view'] ?? '';

if (is_logged_in()) {
    // User is authenticated
    switch ($view) {
        case 'delete_account':
            $page_to_include = __DIR__ . '/../src/views/delete_account_form.php';
            break;
        case 'privacy':
            $page_to_include = __DIR__ . '/../src/views/privacy_policy.php';
            break;
        case 'welcome':
        default:
            $page_to_include = __DIR__ . '/../src/views/welcome_dashboard.php';
            break;
    }
} else {
    // User is a guest (not authenticated)
    switch ($view) {
        case 'register':
            $page_to_include = __DIR__ . '/../src/views/register_form.php';
            break;
        case 'privacy':
            $page_to_include = __DIR__ . '/../src/views/privacy_policy.php';
            break;
        case 'login':
            $page_to_include = __DIR__ . '/../src/views/login_form.php';
            break;
        default:
            $page_to_include = __DIR__ . '/../src/views/guest_home.php';
            break;
    }
}


// 3. VIEW RENDERING
// -----------------------------------------------------------------------------
// Include the determined view file. We check if the file exists as a security measure.
if (file_exists($page_to_include)) {
    require_once $page_to_include;
} else {
    // If the view file doesn't exist, log the error and show a generic 404 page.
    error_log("Routing error: View file not found at '{$page_to_include}'");
    http_response_code(404);
    // In a real app, you would include a nice 404.php view here.
    exit('Error: Page not found.');
}
?>