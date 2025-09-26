<?php
// secure_app/src/views/privacy_policy.php

/**
 * View for the Privacy Policy page.
 * This is a static content page.
 */

// Set the page title
$page_title = 'Privacy Policy';

// Include the header partial
require_once __DIR__ . '/partials/header.php';
?>

<!-- Page-specific content starts here -->
<div class="page-header">
    <h2>Privacy Policy</h2>
</div>

<div class="static-content">
    <p><strong>Effective Date: September 27, 2025</strong></p>

    <p>This Privacy Policy explains how Justice League ("we," "us," or "our") collects, uses,
    discloses, and protects your personal data when you use our web application. We are committed to
    protecting your privacy and handling your data transparently, in compliance with the General Data Protection
    Regulation (GDPR), the Philippine Data Privacy Act of 2012 (Republic Act No. 10173), and other relevant data protection laws.</p>

    <h3>1. What Personal Data We Collect</h3>
    <p>We adhere to the principle of <strong>Data Minimization</strong>, collecting only the data necessary for the purposes
    outlined in this policy. The personal data we collect includes:</p>
    <ul>
        <li><strong>Full Name:</strong> For account identification and personalization.</li>
        <li><strong>Email Address:</strong> For account login, communication, and password recovery.</li>
        <li><strong>Password:</strong> Stored as a securely hashed value; never in plain text.</li>
    </ul>

    <h3>2. How We Use Your Personal Data</h3>
    <p>We use the collected data for the following purposes:</p>
    <ul>
        <li>To create and manage your user account.</li>
        <li>To provide and maintain our services.</li>
        <li>To prevent fraud and enhance security, including mitigating brute-force attacks.</li>
        <li>To fulfill legal obligations and enforce our terms of service.</li>
    </ul>

    <h3>3. Data Retention and Deletion</h3>
    <p>We retain your personal data only for as long as your account is active. You have the right to delete your account at any time from your dashboard. Upon account deletion, all your personal data is permanently and irreversibly removed from our systems.</p>

    <h3>4. Data Security</h3>
    <p>We are committed to ensuring the security of your data. We implement appropriate technical and organizational
    measures to protect your personal data from unauthorized access, alteration, disclosure, or destruction. These measures include strong password hashing, protection against common web vulnerabilities like XSS and CSRF, and secure session management.</p>
    
    <h3>5. Your Data Protection Rights</h3>
    <p>You have several rights regarding your personal data, including:</p>
    <ul>
        <li><strong>The Right to Access:</strong> You can request copies of your personal data.</li>
        <li><strong>The Right to Rectification:</strong> You can request that we correct any information you believe is inaccurate.</li>
        <li><strong>The Right to Erasure (Right to be Forgotten):</strong> You can delete your account and all associated data at any time.</li>
    </ul>
    <p>To exercise any of these rights, please contact us at justice@league.dc</p>

    <h3>6. Third-Party Disclosure</h3>
    <p>We do not sell, trade, or otherwise transfer your personally identifiable information to outside parties.</p>

</div>
<!-- Page-specific content ends here -->

<?php
// Include the footer partial
require_once __DIR__ . '/partials/footer.php';
?>