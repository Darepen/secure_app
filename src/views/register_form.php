<?php
// secure_app/src/views/register_form.php

/**
 * View for the User Registration Form
 */

// Set the page title
$page_title = 'Register';

// Include the header partial
require_once __DIR__ . '/partials/header.php';

// Generate a CSRF token for the registration form
$csrf_token = generate_csrf_token();

// Check for any messages/errors passed from the controller
$error_message = $_SESSION['error_message'] ?? null;
unset($_SESSION['error_message']);

$success_message = $_SESSION['success_message'] ?? null;
unset($_SESSION['success_message']);

// Get any previously submitted data to repopulate the form (improves UX on error)
$submitted_data = $_SESSION['submitted_data'] ?? [];
unset($_SESSION['submitted_data']);

?>

<!-- Page-specific content starts here -->
<div class="page-header">
    <h2>Create an Account</h2>
</div>

<?php if ($error_message): ?>
    <div class="message error">
        <?php echo escape_html($error_message); ?>
    </div>
<?php endif; ?>

<?php if ($success_message): ?>
    <div class="message success">
        <?php echo escape_html($success_message); ?>
        <p>You can now <a href="index.php?view=login">log in</a>.</p>
    </div>
<?php else: // Only show the form if registration was not successful ?>
    <form action="auth.php?action=register" method="POST" class="form-container">
        
        <!-- SECURITY: Anti-CSRF Token -->
        <input type="hidden" name="csrf_token" value="<?php echo escape_html($csrf_token); ?>">

        <div class="form-group">
            <label for="fullName">Full Name:</label>
            <input type="text" id="fullName" name="fullName" value="<?php echo escape_html($submitted_data['fullName'] ?? ''); ?>" required minlength="2" maxlength="100">
        </div>

        <div class="form-group">
            <label for="email">Email Address:</label>
            <input type="email" id="email" name="email" value="<?php echo escape_html($submitted_data['email'] ?? ''); ?>" required maxlength="100">
        </div>

        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required minlength="12" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*]).{12,}" title="Password must be at least 12 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character (!@#$%^&*).">
            <small>Minimum 12 characters, with uppercase, lowercase, number, and a special character.</small>
        </div>
        
        <div class="form-group">
            <label for="password_confirm">Confirm Password:</label>
            <input type="password" id="password_confirm" name="password_confirm" required>
        </div>

        <div class="form-group privacy-statement">
            <label for="agreePrivacy"><input type="checkbox" id="agreePrivacy" name="agreePrivacy" required>I have read and agree to the <a href="index.php?view=privacy" target="_blank">Privacy Policy</a>.</label>
        </div>

        <button type="submit">Register</button>
    </form>
    <p class="text-center" style="margin-top: 20px;">Already have an account? <a href="index.php?view=login">Login here</a>.</p>
<?php endif; ?>
<!-- Page-specific content ends here -->

<?php
// Include the footer partial
require_once __DIR__ . '/partials/footer.php';
?>