<?php
require_once '../Config/Database.php';
require_once '../Models/Tournment.php';

$database = new Database();
$db = $database->getConnection();
$tournament = new Tournament($db);

// Get tournament ID from URL
if (!isset($_GET['tournament_id'])) {
    header('Location: view_tournaments.php');
    exit();
}

$tournament_id = $_GET['tournament_id'];

// Get tournament details
$tournament_details = $tournament->getTournamentById($tournament_id);
if (!$tournament_details) {
    header('Location: view_tournaments.php');
    exit();
}

// Get participants
$participants = $tournament->getParticipants($tournament_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Assets/CSS/admin_style.css">
    <title>Tournament Participants</title>
</head>
<body>
    <div class="sidebar" id="sidebar">
        <ul>
            <li><a href="./registered_users.php">Registered Users</a></li>
            <li><a href="./host_tournament.php">Host a Tournament</a></li>
            <li><a href="./view_tournaments.php">View Hosted Tournaments</a></li>
            <li><a href="./tournament_participants.php" class="active">Tournament Participants</a></li>
        </ul>
    </div>

    <div class="main-wrapper">
        <nav class="navbar">
            <div class="menu-icon" onclick="toggleMenu()">☰</div>
            <h1>Tournament Participants</h1>
            <button class="logout-button" onclick="logout()">Logout</button>
        </nav>

        <div class="main-content">
            <h2><?php echo htmlspecialchars($tournament_details['tournament_name']); ?> - Participants</h2>
            
            <?php if (!empty($participants)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Participant Name</th>
                            <th>Email</th>
                            <th>Join Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($participants as $participant): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($participant['username']); ?></td>
                                <td><?php echo htmlspecialchars($participant['email']); ?></td>
                                <td><?php echo date('F j, Y g:i A', strtotime($participant['joined_at'])); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No participants have joined this tournament yet.</p>
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