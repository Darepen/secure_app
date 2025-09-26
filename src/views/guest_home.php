<?php
// secure_app/src/views/guest_home.php

/**
 * View for the Guest Homepage
 *
 * This is the main landing page for users who are not logged in.
 * It includes the header and footer partials.
 */

// Set the page title for this specific view
$page_title = 'Welcome';

// Include the header partial
require_once __DIR__ . '/partials/header.php';
?>

<!-- Page-specific content starts here -->
<div class="page-header">
    <h2>Welcome to Our Application!</h2>
</div>

<p class="text-center">This application demonstrates a hardened, secure user management system built with Privacy by Design principles.</p>

<div class="text-center" style="margin-top: 30px;">
    <p>Please <a href="index.php?view=register">Register</a> or <a href="index.php?view=login">Log In</a> to access the secure content.</p>
</div>
<!-- Page-specific content ends here -->

<?php
// Include the footer partial
require_once __DIR__ . '/partials/footer.php';
?>