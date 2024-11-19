<?php
require_once '../Config/Database.php';
require_once '../Models/User.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

// Fetch only users (excluding admin)
$users = $user->getAllUsers('user'); // Pass the role or use a query to filter
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Assets/CSS/admin_style.css">
    <title>Registered Users</title>
</head>
<body>
    <div class="main-content">
        <h2>Registered Users</h2>
        
        <!-- Back Button -->
        <a href="admin_dashboard.php">
            <button class="back-button">Back to Dashboard</button>
        </a>

        <table class="user-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['id']); ?></td>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo htmlspecialchars($user['role']); ?></td>
                        <td>
                            <button class="edit-btn" onclick="editUser(<?php echo $user['id']; ?>)">Edit</button>
                            <button class="delete-btn" onclick="deleteUser(<?php echo $user['id']; ?>)">Delete</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
        // Function to handle editing a user
        function editUser(userId) {
            window.location.href = `edit_user.php?id=${userId}`;
        }

        // Function to handle deleting a user
        function deleteUser(userId) {
            if (confirm("Are you sure you want to delete this user?")) {
                window.location.href = `delete_user.php?id=${userId}`;
            }
        }
    </script>
</body>
</html>
