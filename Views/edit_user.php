<?php
require_once '../Config/Database.php';
require_once '../Models/User.php';

$database = new Database();
$db = $database->getConnection();

$userModel = new User($db);

// Fetch user details based on the ID passed in the URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $userId = intval($_GET['id']);
    $user = $userModel->getUserById($userId);

    if (!$user) {
        // If no user is found, redirect to the users list or show an error
        header("Location: registered_users.php");
        exit();
    }
} else {
    // If no ID is passed in the URL, redirect
    header("Location: registered_users.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Assets/CSS/admin_style.css">
    <title>Edit User</title>
</head>
<body>
    <div class="main-content">
        <h2>Edit User</h2>
        <form method="POST" action="update_user.php">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($user['id']); ?>">

            <label for="username">Username</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

            <!-- Removed the role field as you don't want to allow editing the role -->

            <button type="submit">Update User</button>
        </form>
    </div>
</body>
</html>
