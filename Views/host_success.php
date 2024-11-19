<?php
session_start();
if (isset($_SESSION['success_message'])) {
    echo "<div class='success'>" . $_SESSION['success_message'] . "</div>";
    unset($_SESSION['success_message']); // Remove after displaying
} else {
    echo "<div class='error'>No success message found.</div>";
}
?>
