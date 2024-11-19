<?php 
// Include necessary files
require_once '../Config/Database.php';
require_once '../Models/Tournment.php';

// Create a new database connection
$database = new Database();
$db = $database->getConnection();

// Instantiate the Tournament object
$tournament = new Tournament($db);

// Fetch available tournaments
$tournaments = $tournament->getAvailableTournaments();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Assets/CSS/admin_style.css">
    <title>Admin Dashboard</title>
    <style>
        .tournament-list {
            margin-top: 20px;
            list-style: none;
            padding: 0;
        }

        .tournament-list li {
            padding: 12px;
            border: 1px solid #ddd;
            margin-bottom: 10px;
            border-radius: 4px;
            background-color: #f9f9f9;
        }

        .tournament-list li:hover {
            background-color: #f0f0f0;
        }

        .tournament-list a {
            text-decoration: none;
            color: #333;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .tournament-info {
            flex-grow: 1;
        }

        .participant-count {
            background-color: #4CAF50;
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.9em;
        }
    </style>
</head>
<body>
    <div class="sidebar" id="sidebar">
        <ul>
            <li><a href="./registered_users.php">Registered Users</a></li>
            <li><a href="./host_tournament.php">Host a Tournament</a></li>
            <li><a href="./view_tournaments.php">View Hosted Tournaments</a></li>
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
            <h2>Welcome, Admin!</h2>
            <p>Select an option from the menu to manage the platform.</p>

            <!-- List of tournaments with participants link -->
            <!-- <h3>Recent Tournaments</h3> -->
            <!-- <?php if($tournaments): ?>
                <ul class="tournament-list">
                    <?php foreach ($tournaments as $tournament): ?>
                        <li>
                            <a href="tournament_participants.php?tournament_id=<?php echo $tournament['id']; ?>">
                                <div class="tournament-info">
                                    <?php echo htmlspecialchars($tournament['name']); ?>
                                    <span style="color: #666; font-size: 0.9em;">
                                        - <?php echo date('M d, Y', strtotime($tournament['date'])); ?>
                                    </span>
                                </div>
                                <span class="participant-count">
                                    <?php echo $tournament['current_participants']; ?>/<?php echo $tournament['max_participants']; ?> participants
                                </span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No tournaments available.</p>
            <?php endif; ?> -->
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