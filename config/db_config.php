<?php
// secure_app/config/db_config.php (FINAL VERSION)

/**
 * Hardened Database, Session, and Application Configuration
 */

// 1. ERROR HANDLING (Production settings)
error_reporting(0);
ini_set('display_errors', '0');
ini_set('log_errors', '1');

// 2. BASE URL CONFIGURATION
// !!! THIS IS THE CRITICAL LINE THAT WAS MISSING !!!
// It defines the absolute URL to your public folder and MUST end with a slash.
define('BASE_URL', 'http://localhost/CyberLaw/TSA%202/secure_app/public/');

// 3. DATABASE CREDENTIALS
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'secure_app_user');
define('DB_PASSWORD', 'w5hB_zCkVQD6GOix'); // The correct password you generated
define('DB_NAME', 'secure_app_db');

// 4. DATABASE CONNECTION
$mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
if ($mysqli->connect_errno) {
    error_log('Database Connection Failure: (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
    http_response_code(500);
    exit('A critical error occurred. Please try again later.');
}
$mysqli->set_charset("utf8mb4");

// 5. HARDENED SESSION MANAGEMENT
ini_set('session.use_only_cookies', 1);
session_set_cookie_params([
    'lifetime' => 1800,
    'path' => '/',
    'domain' => '',
    'secure' => isset($_SERVER['HTTPS']),
    'httponly' => true,
    'samesite' => 'Strict'
]);
session_start();

if (!isset($_SESSION['session_regenerated'])) {
    session_regenerate_id(true);
    $_SESSION['session_regenerated'] = true;
}

?>