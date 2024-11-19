<?php
// require_once '../Models/User.php';
// require_once '../Config/Database.php'; 

// header('Content-Type: text/html'); 

// try {
//     $database = new Database();
//     $db = $database->getConnection();

//     $user = new User($db);

//     $user->username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
//     $user->email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
//     $user->password = $_POST['password'];
//     $user->role = filter_var($_POST['role'], FILTER_SANITIZE_STRING);

//     if (empty($user->username) || empty($user->email) || empty($user->password)) {
//         echo "<link rel='stylesheet' href='../Assets/CSS/admin_style.css'>";
//         echo "<div class='message error'>Please fill in all fields.</div>";
//         exit;
//     }

//     if (!filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
//         echo "<link rel='stylesheet' href='../Assets/CSS/admin_style.css'>";
//         echo "<div class='message error'>Please enter a valid email address.</div>";
//         exit;
//     }

//     if ($user->register()) {
//         echo "<link rel='stylesheet' href='../Assets/CSS/admin_style.css'>";
//         echo "<div class='message success'>
//                 Registration successful!
//                 <br>
//                 <button onclick='window.location.href=\"../index.php\"'>Go back to Login</button>
//               </div>";
//     } else {
//         echo "<link rel='stylesheet' href='../Assets/CSS/admin_style.css'>";
//         echo "<div class='message error'>Registration failed. Please try again.</div>";
//     }
// } catch (Exception $e) {
//     echo "<link rel='stylesheet' href='../Assets/CSS/admin_style.css'>";
//     echo "<div class='message error'>An error occurred. Please try again.</div>";
// }
?>
<?php
// Start the session
session_start();

// Include necessary files
require_once '../Models/User.php';
require_once '../Config/Database.php';

// Set the Content-Type header to return HTML content
header('Content-Type: text/html');

try {
    // Instantiate the database connection
    $database = new Database();
    $db = $database->getConnection();

    // Instantiate the User object
    $user = new User($db);

    // Sanitize and validate input
    $user->username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $user->email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $user->password = $_POST['password'];
    $user->role = filter_var($_POST['role'], FILTER_SANITIZE_STRING);

    // Perform basic validation
    if (empty($user->username) || empty($user->email) || empty($user->password) || empty($user->role)) {
        echo "<link rel='stylesheet' href='../Assets/CSS/admin_style.css'>";
        echo "<div class='message error'>Please fill in all fields.</div>";
        exit;
    }

    if (!filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
        echo "<link rel='stylesheet' href='../Assets/CSS/admin_style.css'>";
        echo "<div class='message error'>Please enter a valid email address.</div>";
        exit;
    }

    // Attempt to register the user
    if ($user->register()) {
        // If registration is successful, redirect based on the role
        $_SESSION['user_id'] = $user->id;  // Store the user ID in the session
        $_SESSION['username'] = $user->username;
        $_SESSION['role'] = $user->role;

        echo "<link rel='stylesheet' href='../Assets/CSS/admin_style.css'>";
        echo "<div class='message success'>
                Registration successful!
                <br>
                <button onclick='window.location.href=\"../index.php\"'>Go back to Login</button>
              </div>";
    } else {
        // If registration fails, show an error message
        echo "<link rel='stylesheet' href='../Assets/CSS/admin_style.css'>";
        echo "<div class='message error'>Registration failed. Please try again.</div>";
    }
} catch (Exception $e) {
    // Catch any exceptions and display an error message
    echo "<link rel='stylesheet' href='../Assets/CSS/admin_style.css'>";
    echo "<div class='message error'>An error occurred: " . $e->getMessage() . "</div>";
}
?>
