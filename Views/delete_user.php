<?php
require_once '../Config/Database.php';
require_once '../Models/User.php';

$database = new Database();
$db = $database->getConnection();

$userModel = new User($db);

// Check if the 'id' parameter is passed in the URL
if (isset($_GET['id'])) {
    $userId = intval($_GET['id']); // Get the user ID from the URL

    // Call the deleteUser function from the User model
    if ($userModel->deleteUser($userId)) {
        // If user is deleted successfully, redirect to registered users page
        header("Location: registered_users.php");
        exit();
    } else {
        echo "Failed to delete user!";
    }
} else {
    echo "User ID not provided!";
}
?>
