<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Assets/CSS/admin_style.css">
    <title>Host Tournament</title>
</head>
<body>
    <div class="sidebar" id="sidebar">
        <ul>
            <li><a href="./registered_users.php">Registered Users</a></li>
            <li><a href="./host_tournament.php">Host a Tournament</a></li>
            <li><a href="./view_tournaments.php">View Hosted Tournaments</a></li>
            <li><a href="#">Tournament Participants</a></li>
        </ul>
    </div>

    <div class="main-wrapper">
        <nav class="navbar">
            <div class="menu-icon" onclick="toggleMenu()">☰</div>
            <h1>Host Tournament</h1>
            <button class="logout-button" onclick="logout()">Logout</button>
        </nav>

        <div class="main-content">
            <!-- Tournament Hosting Form -->
            <form action="process_tournament.php" method="POST" class="tournament-form">
                <div class="form-group">
                    <label for="tournament_name">Tournament Name:</label>
                    <input type="text" id="tournament_name" name="tournament_name" required>
                </div>

                <div class="form-group">
                    <label for="tournament_date">Tournament Date:</label>
                    <input type="date" id="tournament_date" name="tournament_date" required>
                </div>

                <div class="form-group">
                    <label for="venue">Venue:</label>
                    <input type="text" id="venue" name="venue" required>
                </div>

                <div class="form-group">
                    <label for="max_participants">Maximum Participants:</label>
                    <input type="number" id="max_participants" name="max_participants" required>
                </div>

                <button type="submit" class="submit-btn">Host Tournament</button>
                <a href="admin_dashboard.php" class="back-btn">Back to Dashboard</a>
            </form>
        </div>
    </div>

    <script>
        // Toggle menu for responsive sidebar
        function toggleMenu() {
            const sidebar = document.getElementById('sidebar');
            const mainWrapper = document.querySelector('.main-wrapper');
            sidebar.classList.toggle('active');
            mainWrapper.classList.toggle('shifted');
        }

        // Logout function
        function logout() {
            window.location.href = '../index.php';
        }
    </script>
</body>
</html>
