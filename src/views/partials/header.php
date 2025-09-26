<?php
// secure_app/src/views/partials/header.php (FINAL VERSION)

require_once __DIR__ . '/../../../src/lib/security.php';
send_security_headers();
$page_title = isset($page_title) ? $page_title : 'Secure Web Application';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo escape_html($page_title); ?></title>
    
    <!-- This now uses the BASE_URL constant for a reliable path -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/style.css">
    
    <!-- The base href ensures all other relative links work correctly -->
    <base href="<?php echo BASE_URL; ?>">
</head>
<body>

<header>
    <div class="container">
        <h1><a href="<?php echo BASE_URL; ?>index.php">Secure Web Application</a></h1>
        <nav>
            <ul>
                <?php if (is_logged_in()): ?>
                    <li><a href="index.php?view=welcome">Dashboard</a></li>
                    <li><a href="auth.php?action=logout">Logout</a></li>
                <?php else: ?>
                    <li><a href="index.php?view=login">Login</a></li>
                    <li><a href="index.php?view=register">Register</a></li>
                    <li><a href="index.php?view=privacy">Privacy Policy</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</header>

<main>
    <div class="container">