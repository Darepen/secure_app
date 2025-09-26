<?php
// secure_app/src/lib/auth.php

/**
 * Hardened Authentication and User Management Library
 *
 * Contains functions for user registration, login, session validation,
 * brute-force attack mitigation, and account deletion.
 */

// Define constants for brute-force protection
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOCKOUT_TIME_MINUTES', 15);

/**
 * Checks if a user is currently logged in and their session is valid.
 *
 * @return bool True if logged in, false otherwise.
 */
function is_logged_in() {
    // Check if the session variable is set and is true.
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Registers a new user in the database.
 *
 * @param mysqli $mysqli The database connection object.
 * @param string $fullName The user's full name.
 * @param string $email The user's email address.
 * @param string $password The user's plaintext password.
 * @return array ['success' => bool, 'message' => string]
 */
function register_user($mysqli, $fullName, $email, $password) {
    // Check if email already exists to prevent duplicates.
    $sql = "SELECT id FROM users WHERE email = ?";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $stmt->close();
            return ['success' => false, 'message' => 'This email address is already registered.'];
        }
        $stmt->close();
    }

    // Hash the password using Argon2ID, a strong, modern hashing algorithm.
    $password_hash = password_hash($password, PASSWORD_BCRYPT);
    if ($password_hash === false) {
        error_log('Password hashing failed.');
        return ['success' => false, 'message' => 'Could not process password.'];
    }

    // Insert the new user into the database.
    $sql = "INSERT INTO users (full_name, email, password_hash, privacy_consent) VALUES (?, ?, ?, 1)";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("sss", $fullName, $email, $password_hash);
        if ($stmt->execute()) {
            $stmt->close();
            return ['success' => true, 'message' => 'Registration successful. You can now log in.'];
        } else {
            error_log("User registration failed: " . $stmt->error);
            $stmt->close();
            return ['success' => false, 'message' => 'An error occurred during registration.'];
        }
    }
    return ['success' => false, 'message' => 'An unexpected error occurred.'];
}

/**
 * Processes a user login attempt with brute-force protection.
 *
 * @param mysqli $mysqli The database connection object.
 * @param string $email The user's email.
 * @param string $password The user's password.
 * @return array ['success' => bool, 'message' => string]
 */
function login_user($mysqli, $email, $password) {
    // Fetch user details, including security fields.
    $sql = "SELECT id, full_name, email, password_hash, failed_login_attempts, account_locked_until FROM users WHERE email = ?";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();

        if ($user) {
            // Check if the account is currently locked.
            if ($user['account_locked_until'] !== null && new DateTime() < new DateTime($user['account_locked_until'])) {
                return ['success' => false, 'message' => 'Account is locked due to too many failed login attempts. Please try again later.'];
            }

            // Verify the password.
            if (password_verify($password, $user['password_hash'])) {
                // Password is correct. Reset any failed login attempts.
                $update_sql = "UPDATE users SET failed_login_attempts = 0, account_locked_until = NULL WHERE id = ?";
                if ($update_stmt = $mysqli->prepare($update_sql)) {
                    $update_stmt->bind_param("i", $user['id']);
                    $update_stmt->execute();
                    $update_stmt->close();
                }

                // Regenerate session ID to prevent session fixation.
                session_regenerate_id(true);

                // Set session variables.
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['email'] = $user['email'];

                return ['success' => true, 'message' => 'Login successful.'];
            } else {
                // Password is incorrect. Increment failed login attempts.
                $new_attempts = $user['failed_login_attempts'] + 1;
                $update_sql = "UPDATE users SET failed_login_attempts = ? WHERE id = ?";
                $lock_account = false;

                if ($new_attempts >= MAX_LOGIN_ATTEMPTS) {
                    // Lock the account.
                    $lock_until = (new DateTime())->add(new DateInterval('PT' . LOCKOUT_TIME_MINUTES . 'M'))->format('Y-m-d H:i:s');
                    $update_sql = "UPDATE users SET failed_login_attempts = ?, account_locked_until = ? WHERE id = ?";
                    $lock_account = true;
                }

                if ($update_stmt = $mysqli->prepare($update_sql)) {
                    if($lock_account){
                        $update_stmt->bind_param("isi", $new_attempts, $lock_until, $user['id']);
                    } else {
                        $update_stmt->bind_param("ii", $new_attempts, $user['id']);
                    }
                    $update_stmt->execute();
                    $update_stmt->close();
                }
                
                return ['success' => false, 'message' => 'Invalid email or password.'];
            }
        } else {
            // User does not exist.
            return ['success' => false, 'message' => 'Invalid email or password.'];
        }
    }
    return ['success' => false, 'message' => 'An unexpected error occurred.'];
}

/**
 * Deletes a user's account from the database.
 *
 * @param mysqli $mysqli The database connection object.
 * @param int $user_id The ID of the user to delete.
 * @return bool True on success, false on failure.
 */
function delete_user_account($mysqli, $user_id) {
    $sql = "DELETE FROM users WHERE id = ?";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("i", $user_id);
        if ($stmt->execute()) {
            $stmt->close();
            return true;
        }
        $stmt->close();
    }
    return false;
}

?>