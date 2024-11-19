
<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit;
}

// Include necessary files
require_once '../Config/Database.php';

// Create database connection
$database = new Database();
$db = $database->getConnection();

// Fetch user data
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE id = :user_id";
$stmt = $db->prepare($query);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();

$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
    <style>
        /* Base styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        /* Sidebar styles */
        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: #2c3e50;
            position: fixed;
            left: 0;
            top: 0;
            transition: 0.3s;
            padding-top: 60px;
        }

        .sidebar.active {
            left: -250px;
        }

        .sidebar ul {
            list-style: none;
        }

        .sidebar ul li {
            padding: 15px 25px;
        }

        .sidebar ul li a {
            color: white;
            text-decoration: none;
            font-size: 16px;
            display: block;
            transition: 0.3s;
        }

        .sidebar ul li a:hover {
            color: #3498db;
        }

        /* Main content wrapper */
        .main-wrapper {
            margin-left: 250px;
            transition: 0.3s;
        }

        .main-wrapper.shifted {
            margin-left: 0;
        }

        /* Navbar styles */
        .navbar {
            background-color: #3498db;
            padding: 15px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
        }

        .menu-icon {
            font-size: 24px;
            cursor: pointer;
            display: none;
        }

        .logout-button {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
        }

        .logout-button:hover {
            background-color: #c0392b;
        }

        /* Main content area */
        .main-content {
            padding: 25px;
        }

        /* Profile Section */
        .profile-section {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-top: 20px;
        }

        .profile-section h2 {
            color: #2c3e50;
            margin-bottom: 15px;
        }

        .profile-info {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .profile-info p {
            font-size: 16px;
            color: #333;
        }

        .profile-info strong {
            color: #3498db;
        }

        /* Sidebar toggle styles */
        @media (max-width: 768px) {
            .menu-icon {
                display: block;
            }

            .sidebar {
                left: -250px;
                z-index: 1000;
            }

            .sidebar.active {
                left: 0;
            }

            .main-wrapper {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <div class="sidebar" id="sidebar">
        <ul>
            <li><a href="./available_tournaments.php">Available Tournaments</a></li>
            <li><a href="./my_tournaments.php">My Tournaments</a></li>
            <li><a href="./profile.php">My Profile</a></li>
        </ul>
    </div>

    <div class="main-wrapper">
        <nav class="navbar">
            <div class="menu-icon" onclick="toggleMenu()">☰</div>
            <h1>My Profile</h1>
            <button class="logout-button" onclick="logout()">Logout</button>
        </nav>

        <div class="main-content">
            <div class="profile-section">
                <h2>Profile Information</h2>
                <div class="profile-info">
                    <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                    <!-- Add other profile fields here -->
                </div>
            </div>
        </div>
    </div>

    <script>
        // Sidebar toggle function
        function toggleMenu() {
            const sidebar = document.getElementById('sidebar');
            const mainWrapper = document.querySelector('.main-wrapper');
            sidebar.classList.toggle('active');
            mainWrapper.classList.toggle('shifted');
        }

        // Logout function
        function logout() {
            if (confirm('Are you sure you want to logout?')) {
                window.location.href = '../Controllers/LogoutController.php';
            }
        }
    </script>
</body>
</html>
