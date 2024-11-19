<?php
require_once '../Config/Database.php';
require_once '../Models/User.php';

$database = new Database();
$db = $database->getConnection();

$userModel = new User($db);

// Check if form data is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = intval($_POST['id']);
    $username = htmlspecialchars(strip_tags($_POST['username']));
    $email = htmlspecialchars(strip_tags($_POST['email']));

    // Update the user in the database (no role is updated)
    $updateSuccess = $userModel->updateUser($userId, $username, $email);

    if ($updateSuccess) {
        // Redirect to a confirmation page after a successful update
        header("Location: user_updated.php");
        exit();
    } else {
        echo "Failed to update the user!";
    }
}
?>
