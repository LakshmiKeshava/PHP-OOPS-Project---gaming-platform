<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
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

        /* Welcome section */
        .welcome-section {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }

        .welcome-section h2 {
            color: #2c3e50;
            margin-bottom: 10px;
        }

        /* Tournament Grid */
        .tournament-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            padding: 20px 0;
        }

        .tournament-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 20px;
            transition: transform 0.3s;
        }

        .tournament-card:hover {
            transform: translateY(-5px);
        }

        .tournament-card h3 {
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .tournament-info {
            margin: 15px 0;
            color: #666;
        }

        .tournament-info p {
            margin: 5px 0;
        }

        .tournament-status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 14px;
            margin-top: 10px;
        }

        .status-open {
            background-color: #2ecc71;
            color: white;
        }

        .status-closed {
            background-color: #e74c3c;
            color: white;
        }

        .join-button {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            margin-top: 10px;
            transition: background-color 0.3s;
        }

        .join-button:hover {
            background-color: #2980b9;
        }

        .join-button.disabled {
            background-color: #bdc3c7;
            cursor: not-allowed;
        }

        /* Loading spinner */
        .loading {
            display: none;
            text-align: center;
            padding: 20px;
        }

        .loading:after {
            content: '⚡';
            animation: loading 1s infinite;
        }

        @keyframes loading {
            0% { opacity: 0.2; }
            50% { opacity: 1; }
            100% { opacity: 0.2; }
        }

        /* Responsive design */
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

            .tournament-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="sidebar" id="sidebar">
        <ul>
            <li><a href="./available_tournaments.php">Available Tournaments</a></li>
            <li><a href="./my_tournaments.php">My Tournaments</a></li>
            <!-- <li><a href="./tournament_history.php">Tournament History</a></li> -->
            <li><a href="./profile.php">My Profile</a></li>
        </ul>
    </div>

    <div class="main-wrapper">
        <nav class="navbar">
            <div class="menu-icon" onclick="toggleMenu()">☰</div>
            <h1>User Dashboard</h1>
            <button class="logout-button" onclick="logout()">Logout</button>
        </nav>

        <div class="main-content">
            <div class="welcome-section">
                <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username'] ?? 'User'); ?>!</h2>
                <!-- <p>Here are the available tournaments you can join. Check out the details and register for the ones you're interested in.</p> -->
            </div>

            <div id="loading" class="loading">Loading tournaments...</div>

            <div class="tournament-grid">
                <!-- <?php
                // Include necessary files
                require_once '../Config/Database.php';
                require_once '../Models/Tournment.php';

                try {
                    // Create database connection
                    $database = new Database();
                    $db = $database->getConnection();

                    // Create tournament object
                    $tournament = new Tournament($db);

                    // Get all available tournaments
                    $tournaments = $tournament->getAvailableTournaments();

                    if ($tournaments) {
                        foreach($tournaments as $tournament) {
                            $statusClass = $tournament['status'] === 'open' ? 'status-open' : 'status-closed';
                            $buttonDisabled = $tournament['status'] === 'closed' ? 'disabled' : '';
                            
                            echo "<div class='tournament-card'>";
                            echo "<h3>" . htmlspecialchars($tournament['name']) . "</h3>";
                            echo "<div class='tournament-info'>";
                            echo "<p><strong>Date:</strong> " . htmlspecialchars($tournament['date']) . "</p>";
                            echo "<p><strong>Location:</strong> " . htmlspecialchars($tournament['location']) . "</p>";
                            echo "<p><strong>Participants:</strong> " . htmlspecialchars($tournament['current_participants']) . "/" . htmlspecialchars($tournament['max_participants']) . "</p>";
                            echo "</div>";
                            echo "<span class='tournament-status {$statusClass}'>" . ucfirst(htmlspecialchars($tournament['status'])) . "</span>";
                            if (!$buttonDisabled) {
                                echo "<button class='join-button' onclick='joinTournament(" . $tournament['id'] . ")'>Join Tournament</button>";
                            } else {
                                echo "<button class='join-button disabled' disabled>Tournament Closed</button>";
                            }
                            echo "</div>";
                        }
                    } else {
                        echo "<p>No tournaments available at the moment.</p>";
                    }
                } catch (Exception $e) {
                    echo "<p>Error loading tournaments. Please try again later.</p>";
                }
                ?> -->
            </div>
        </div>
    </div>

    <script>
        // Toggle sidebar visibility
        function toggleMenu() {
            const sidebar = document.getElementById('sidebar');
            const mainWrapper = document.querySelector('.main-wrapper');
            sidebar.classList.toggle('active');
            mainWrapper.classList.toggle('shifted');
        }

        // Logout function
        function logout() {
            if(confirm('Are you sure you want to logout?')) {
                window.location.href = '../Controllers/LogoutController.php';
            }
        }

        // Join tournament function
        function joinTournament(tournamentId) {
            const loadingDiv = document.getElementById('loading');
            
            if(confirm('Are you sure you want to join this tournament?')) {
                loadingDiv.style.display = 'block';
                
                fetch('../Controllers/JoinTournamentController.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'tournament_id=' + tournamentId
                })
                .then(response => response.json())
                .then(data => {
                    loadingDiv.style.display = 'none';
                    if(data.success) {
                        alert('Successfully joined the tournament!');
                        location.reload();
                    } else {
                        alert(data.message || 'Failed to join tournament');
                    }
                })
                .catch(error => {
                    loadingDiv.style.display = 'none';
                    console.error('Error:', error);
                    alert('An error occurred while joining the tournament');
                });
            }
        }

        // Add this if you want to show a loading state when the page loads
        document.addEventListener('DOMContentLoaded', function() {
            const loadingDiv = document.getElementById('loading');
            loadingDiv.style.display = 'block';
            
            // Hide loading after content is loaded
            setTimeout(() => {
                loadingDiv.style.display = 'none';
            }, 500);
        });
    </script>
</body>
</html>
