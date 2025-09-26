// secure_app/public/js/main.js

/**
 * Main JavaScript file for the application.
 * Contains client-side logic, such as the account deletion countdown.
 */

// Use a "DOMContentLoaded" event listener to ensure the HTML is fully loaded
// before trying to find elements on the page. This is a best practice.
document.addEventListener('DOMContentLoaded', function() {

    // --- Account Deletion Countdown Logic ---

    // Find the necessary elements on the page.
    const countdownElement = document.getElementById('countdown');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    const deleteForm = document.getElementById('deleteForm');

    // Only run the countdown logic if the countdown element actually exists on the current page.
    if (countdownElement && confirmDeleteBtn) {
        let countdown = 5;

        // Set the button's initial text.
        confirmDeleteBtn.textContent = `Please wait ${countdown} seconds...`;

        // Create an interval timer that runs every 1000 milliseconds (1 second).
        const timer = setInterval(function() {
            countdown--; // Decrement the counter

            if (countdown > 0) {
                // If the countdown is still running, update the timer display.
                countdownElement.textContent = countdown;
                confirmDeleteBtn.textContent = `Please wait ${countdown} seconds...`;
            } else {
                // If the countdown has finished:
                clearInterval(timer); // Stop the timer.
                countdownElement.style.display = 'none'; // Hide the number.
                
                // Change the button text and enable it.
                confirmDeleteBtn.textContent = 'I understand the consequences, permanently delete my account';
                confirmDeleteBtn.disabled = false;
            }
        }, 1000);
    }
    
    // You can add more JavaScript logic for other pages here.
    // For example, client-side password strength validation for the registration form.

});