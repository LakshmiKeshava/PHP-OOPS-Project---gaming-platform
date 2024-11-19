<?php
require_once '../Config/Database.php';
require_once '../Models/Tournment.php';

// Create database connection
$database = new Database();
$db = $database->getConnection();

// Instantiate Tournament object
$tournament = new Tournament($db);

// Get all tournaments
$tournaments = $tournament->getAllTournaments();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Assets/CSS/admin_style.css">
    <title>View Hosted Tournaments</title>
    <style>
        .tournament-card {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            background-color: #f9f9f9;
        }
        .view-participants-btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
        }
        .tournament-stats {
            margin: 10px 0;
            color: #666;
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
            <div class="menu-icon" onclick="toggleMenu()">☰</div>
            <h1>View Tournaments</h1>
            <button class="logout-button" onclick="logout()">Logout</button>
        </nav>

        <div class="main-content">
            <h2>All Tournaments</h2>
            
            <?php if (!empty($tournaments)): ?>
                <?php foreach ($tournaments as $tournament): ?>
                    <div class="tournament-card">
                        <h3><?php echo htmlspecialchars($tournament['tournament_name']); ?></h3>
                        <div class="tournament-stats">
                            <p><strong>Date:</strong> <?php echo htmlspecialchars($tournament['tournament_date']); ?></p>
                            <p><strong>Venue:</strong> <?php echo htmlspecialchars($tournament['venue']); ?></p>
                            <p><strong>Participants:</strong> <?php echo $tournament['current_participants']; ?>/<?php echo $tournament['max_participants']; ?></p>
                        </div>
                        <a href="view_tournament_participants.php?tournament_id=<?php echo $tournament['id']; ?>" 
                           class="view-participants-btn">View Participants</a>
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