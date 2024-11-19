<?php
// Start the session to access session variables
session_start();

// Destroy the session to log the user out
session_unset();  // Unset all session variables
session_destroy(); // Destroy the session

// Optionally, you can also delete session cookies if they are set
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}

// Redirect the user to the login page or home page
header("Location: ../Views/login.php"); // Change this to your login page or home page
exit();
?>
