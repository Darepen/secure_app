<?php
// secure_app/src/views/partials/footer.php

/**
 * Reusable Footer Partial
 *
 * This file contains the closing tags for the main content area, the site footer,
 * and the closing </body> and </html> tags. It's included at the bottom of
 * every user-facing view.
 */
?>
    </div> <!-- .container -->
</main>

<footer>
    <div class="container">
        <!-- We use escape_html on the date for good practice, although it's not user-generated -->
        <p>&copy; <?php echo escape_html(date("Y")); ?> Secure App. All rights reserved.</p>
    </div>
</footer>

<!-- JavaScript files are placed at the end of the body for better performance -->
<!-- The path is relative to the base href set in the header -->
<script src="js/main.js"></script>

</body>
</html>