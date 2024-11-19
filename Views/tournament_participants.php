<?php
// Include necessary files
require_once '../Config/Database.php';
require_once '../Models/Tournment.php';  // Tournament Model

// Create a new database connection
$database = new Database();
$db = $database->getConnection();

// Instantiate the Tournament object
$tournament = new Tournament($db);

// Fetch all tournaments (or you can filter based on some criteria)
$tournaments = $tournament->getAllTournaments();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Assets/CSS/admin_style.css">
    <title>View Tournaments</title>
    <style>
        .tournament-card {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            background-color: #f9f9f9;
        }

        .tournament-card h3 {
            font-size: 1.5em;
            margin: 0;
        }

        .tournament-card p {
            font-size: 1em;
            color: #666;
        }

        .view-participants-btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-top: 10px;
        }

        .view-participants-btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="sidebar" id="sidebar">
        <ul>
            <li><a href="./registered_users.php">Registered Users</a></li>
            <li><a href="./host_tournament.php">Host a Tournament</a></li>
            <li><a href="./view_tournaments.php" class="active">View Hosted Tournaments</a></li>
            <li><a href="./tournament_participants.php">Tournament Participants</a></li>
        </ul>
    </div>

    <div class="main-wrapper">
        <nav class="navbar">
            <div class="menu-icon" onclick="toggleMenu()">
                ☰
            </div>
            <h1>Admin Dashboard</h1>
            <button class="logout-button" onclick="logout()">Logout</button>
        </nav>

        <div class="main-content">
            <h2>All Tournaments</h2>

            <?php if (!empty($tournaments)): ?>
                <?php foreach ($tournaments as $tournament): ?>
                    <div class="tournament-card">
                        <h3><?php echo htmlspecialchars($tournament['tournament_name']); ?></h3>
                        <p><strong>Date:</strong> <?php echo htmlspecialchars($tournament['tournament_date']); ?></p>
                        <p><strong>Max Participants:</strong> <?php echo htmlspecialchars($tournament['max_participants']); ?></p>
                        <p><strong>Current Participants:</strong> <?php echo htmlspecialchars($tournament['current_participants']); ?></p>
                        
                        <!-- Link to view participants for this specific tournament -->
                        <!-- <a href="view_tournament_participants.php?tournament_id=<?php echo $tournament['id']; ?>" class="view-participants-btn">View Participants</a> -->
                    <a href="view_tournament_participants.php?tournament_id=<?php echo $tournament['id']; ?>" class="view-participants-btn">View Participants</a>
                
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No tournaments available.</p>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function toggleMenu() {
            const sidebar = document.getElementById('sidebar');
            const mainWrapper = document.querySelector('.main-wrapper');
            sidebar.classList.toggle('active');
            mainWrapper.classList.toggle('shifted');
        }

        function logout() {
            window.location.href = '../index.php';
        }
    </script>
</body>
</html>
