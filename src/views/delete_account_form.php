<?php
// secure_app/src/views/delete_account_form.php

/**
 * View for the Account Deletion Confirmation page.
 */

// Set the page title
$page_title = 'Delete Account Confirmation';

// Include the header partial
require_once __DIR__ . '/partials/header.php';

// The controller (index.php) will have already verified that the user is logged in.

// Generate a CSRF token for the delete form.
$csrf_token = generate_csrf_token();
?>

<!-- Page-specific content starts here -->
<div class="page-header">
    <h2>Are You Absolutely Sure?</h2>
</div>

<div class="warning-box">
    <h3><strong>WARNING: This action is permanent and irreversible.</strong></h3>
    <p>Deleting your account will permanently remove all associated data from our system. Once your account is deleted, it cannot be recovered for any reason.</p>
</div>

<p class="text-center">To proceed with permanent account deletion, please wait for the countdown to finish.</p>
<div class="countdown-timer" id="countdown">5</div>

<div class="text-center" style="margin-top: 20px;">
    <form action="user.php?action=delete" method="POST" id="deleteForm">
        <!-- SECURITY: Anti-CSRF Token -->
        <input type="hidden" name="csrf_token" value="<?php echo escape_html($csrf_token); ?>">
        
        <button type="submit" id="confirmDeleteBtn" class="button-danger" disabled>I understand the consequences, permanently delete my account</button>
    </form>
    <a href="index.php?view=welcome" class="button-secondary">No, take me back to my dashboard!</a>
</div>
<!-- Page-specific content ends here -->

<?php
// Include the footer partial
require_once __DIR__ . '/partials/footer.php';
?>