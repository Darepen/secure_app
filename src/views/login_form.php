<?php
// secure_app/src/views/login_form.php

/**
 * View for the User Login Form
 */

// Set the page title
$page_title = 'Login';

// Include the header partial
require_once __DIR__ . '/partials/header.php';

// Generate a CSRF token for the login form
$csrf_token = generate_csrf_token();

// Check for any error messages passed from the controller via the session
$error_message = $_SESSION['error_message'] ?? null;
unset($_SESSION['error_message']); // Clear the message after displaying it once

// Get any previously submitted email to repopulate the form (improves UX)
$submitted_email = $_SESSION['submitted_email'] ?? '';
unset($_SESSION['submitted_email']); // Clear after use

?>

<!-- Page-specific content starts here -->
<div class="page-header">
    <h2>Login to Your Account</h2>
</div>

<?php if ($error_message): ?>
    <div class="message error">
        <?php echo escape_html($error_message); ?>
    </div>
<?php endif; ?>

<form action="auth.php?action=login" method="POST" class="form-container">
    
    <!-- SECURITY: Anti-CSRF Token -->
    <!-- This hidden field is essential for preventing Cross-Site Request Forgery attacks. -->
    <input type="hidden" name="csrf_token" value="<?php echo escape_html($csrf_token); ?>">

    <div class="form-group">
        <label for="email">Email Address:</label>
        <input type="email" id="email" name="email" value="<?php echo escape_html($submitted_email); ?>" required>
    </div>

    <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
    </div>

    <button type="submit">Login</button>
</form>

<p class="text-center" style="margin-top: 20px;">Don't have an account? <a href="index.php?view=register">Register here</a>.</p>
<!-- A forgot password link would go here in a full application -->

<!-- Page-specific content ends here -->

<?php
// Include the footer partial
require_once __DIR__ . '/partials/footer.php';
?>