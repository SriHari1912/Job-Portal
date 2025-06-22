<?php
session_start(); // Start the session

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    // Set a logout message
    $_SESSION['logout_message'] = 'Successfully logged out!';

    // Unset all session variables
    $_SESSION = array();

    // Destroy the session
    session_destroy();
}

// Redirect to the login page
header("Location: adminlogin.php");
exit; // Ensure no further code is executed
