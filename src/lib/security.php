<?php
// secure_app/src/lib/security.php

/**
 * Hardened Security Library
 *
 * This file contains essential functions for implementing security measures
 * such as CSRF protection and sending security-enhancing HTTP headers.
 */

// ========================================================================
// SECURITY: ANTI-CSRF (Cross-Site Request Forgery) TOKEN FUNCTIONS
// ========================================================================

/**
 * Generates a new CSRF token, stores it in the session, and returns it.
 * This should be called on the page that displays the form.
 *
 * @return string The generated CSRF token.
 */
function generate_csrf_token() {
    // Check if a token already exists for this session. If not, generate one.
    if (empty($_SESSION['csrf_token'])) {
        // Use random_bytes for cryptographically secure random data.
        // bin2hex converts the binary data into a hexadecimal representation.
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Validates the submitted CSRF token against the one stored in the session.
 * This should be called on the script that processes the form submission.
 *
 * @return bool True if the token is valid, false otherwise.
 */
function validate_csrf_token() {
    // Check if the form was submitted (POST request) and if the token is set.
    if (isset($_POST['csrf_token']) && isset($_SESSION['csrf_token'])) {
        // Use hash_equals for a timing-attack-safe comparison.
        if (hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
            // Token is valid, unset it to prevent reuse (one-time use tokens).
            unset($_SESSION['csrf_token']);
            return true;
        }
    }
    // If checks fail, log the attempt and return false.
    error_log('CSRF token validation failed.');
    return false;
}


// ========================================================================
// SECURITY: HTTP SECURITY HEADERS
// ========================================================================

/**
 * Sends a set of strict HTTP security headers to the browser.
 * This function should be called at the very beginning of every user-facing page
 * before any HTML is outputted.
 */
function send_security_headers() {
    // Prevent MIME-sniffing vulnerabilities.
    header("X-Content-Type-Options: nosniff");

    // Prevent clickjacking attacks by disallowing the page to be loaded in an iframe.
    header("X-Frame-Options: DENY");

    // Enable the browser's built-in XSS protection.
    header("X-XSS-Protection: 1; mode=block");

    // Content Security Policy (CSP) - A powerful defense against XSS.
    // This policy is very strict:
    // - default-src 'self': Only allow resources (scripts, images, etc.) from our own domain.
    // - style-src 'self': Only allow stylesheets from our own domain.
    // - script-src 'self': Only allow JavaScript from our own domain.
    // - frame-ancestors 'none': Re-enforces the X-Frame-Options directive.
    // - form-action 'self': Only allow forms to be submitted to our own domain.
    header("Content-Security-Policy: default-src 'self'; style-src 'self'; script-src 'self'; frame-ancestors 'none'; form-action 'self';");
}


// ========================================================================
// SECURITY: XSS (Cross-Site Scripting) MITIGATION
// ========================================================================

/**
 * A simple wrapper for htmlspecialchars to make output escaping more convenient and consistent.
 * This function should be used every time you echo user-provided data to the HTML page.
 *
 * @param string|null $data The data to be escaped.
 * @return string The escaped data.
 */
function escape_html($data) {
    // If data is null, return an empty string.
    if ($data === null) {
        return '';
    }
    // ENT_QUOTES: Escapes both single and double quotes.
    // UTF-8: Specifies the character set to prevent encoding-related issues.
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}
?>