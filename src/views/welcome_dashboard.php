<?php
// secure_app/src/views/welcome_dashboard.php

/**
 * View for the authenticated user's dashboard.
 * This page is only shown to users who are logged in.
 */

// Set the page title
$page_title = 'Dashboard';

// Include the header partial
require_once __DIR__ . '/partials/header.php';

// The controller (index.php) will have already verified that the user is logged in
// before including this file. We can safely access session data.

// Retrieve user's name from the session and escape it for safe display.
$user_full_name = escape_html($_SESSION['full_name']);

// Check for any messages passed via the session (e.g., from other actions)
$error_message = $_SESSION['error_message'] ?? null;
unset($_SESSION['error_message']);

$success_message = $_SESSION['success_message'] ?? null;
unset($_SESSION['success_message']);

?>

<!-- Page-specific content starts here -->
<div class="page-header">
    <h2>Welcome, <?php echo $user_full_name; ?>!</h2>
</div>

<?php if ($error_message): ?>
    <div class="message error">
        <?php echo escape_html($error_message); ?>
    </div>
<?php endif; ?>

<?php if ($success_message): ?>
    <div class="message success">
        <?php echo escape_html($success_message); ?>
    </div>
<?php endif; ?>

<p>This is your secure dashboard. You are successfully logged in.</p>

<div class="dashboard-actions">
    <p>From here you can manage your account.</p>
    <ul>
        <li><a href="index.php?view=delete_account" class="button-danger">Permanently Delete My Account</a></li>
    </ul>
</div>
<!-- Page-specific content ends here -->

<?php
// Include the footer partial
require_once __DIR__ . '/partials/footer.php';
?>