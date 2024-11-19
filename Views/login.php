<?php 
session_start();

require_once '../Config/Database.php';
require_once '../Models/User.php';

// Create a database connection
$database = new Database();
$db = $database->getConnection();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sanitize input
    $email = htmlspecialchars(strip_tags($_POST['email']));
    $password = $_POST['password']; // Don't sanitize password before verification

    // Instantiate User class
    $user = new User($db);
    
    // Set the properties instead of passing as parameters
    $user->email = $email;
    $user->password = $password;

    // Check if the login credentials are correct
    if ($user->login()) {
        // Successful login - use the properties set in the login() method
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_role'] = $user->role;
        $_SESSION['user_email'] = $email;
        $_SESSION['username'] = $user->username;

        // Redirect based on role
        if ($user->role === 'admin') {
            header("Location: admin_dashboard.php");
        } else {
            header("Location: user_dashboard.php");
        }
        exit;
    } else {
        $error_message = "Invalid email or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../Assets/css/style.css">
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <?php if (isset($error_message)) { echo "<p class='error'>$error_message</p>"; } ?>
        <form method="POST" action="">
            <input type="email" name="email" placeholder="Enter email" required>
            <input type="password" name="password" placeholder="Enter password" required>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>