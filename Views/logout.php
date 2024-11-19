<?php
session_start();
session_destroy(); // Destroy all sessions

// Redirect to the login page with a success message
header("Location: login.php?message=Logout successful");
exit();
?>
