<?php
require_once '../Models/User.php';
$database = new Database();
$db = $database->getConnection();

$user = new User($db);

$user->email = $_POST['email'];
$user->password = $_POST['password'];

if ($user->login()) {
    echo "Login successful! Welcome, " . $user->username;
} else {
    echo "Login failed. Invalid credentials.";
}
?>
