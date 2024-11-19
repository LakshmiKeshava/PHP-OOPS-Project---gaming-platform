<div class="back-button-container">
        <button class="back-button" onclick="window.location.href='user_dashboard.php'">Back to Dashboard</button>
    </div>
<?php

require_once '../Config/Database.php';
require_once '../Models/Tournment.php';


$database = new Database();
$db = $database->getConnection();

$tournament = new Tournament($db);

try {
    $tournaments = $tournament->getAvailableTournaments();

    if ($tournaments) {
        foreach ($tournaments as $tournament) {
            echo "<div class='tournament-card'>";
            echo "<h3>" . htmlspecialchars($tournament['tournament_name']) . "</h3>";
            echo "<p><strong>Date:</strong> " . htmlspecialchars($tournament['tournament_date']) . "</p>";
            echo "<p><strong>Venue:</strong> " . htmlspecialchars($tournament['venue']) . "</p>";
            echo "<p><strong>Max Participants:</strong> " . htmlspecialchars($tournament['max_participants']) . "</p>";
            echo "<p><strong>Current Participants:</strong> " . htmlspecialchars($tournament['current_participants']) . "</p>";

            $statusClass = ($tournament['status'] === 'open') ? 'status-open' : 'status-closed';
            echo "<span class='tournament-status {$statusClass}'>" . ucfirst(htmlspecialchars($tournament['status'])) . "</span>";

            $buttonDisabled = ($tournament['status'] === 'closed') ? 'disabled' : '';
            if (!$buttonDisabled) {
                echo "<button class='join-button' onclick='joinTournament(" . $tournament['id'] . ")'>Join Tournament</button>";
            } else {
                echo "<button class='join-button disabled' disabled>Tournament Closed</button>";
            }

            echo "</div>"; 
        }
    } else {
        echo "<p>No tournaments available.</p>";
    }
} catch (PDOException $e) {
    echo "<p>Error: " . $e->getMessage() . "</p>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Tournaments</title>
    <style>
        .back-button {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 20px;
        }

        .back-button:hover {
            background-color: #2980b9;
        }

        .tournament-card {
            background-color: #fff;
            padding: 20px;
            margin: 10px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
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
            margin-top: 10px;
            transition: background-color 0.3s;
        }

        .join-button.disabled {
            background-color: #bdc3c7;
            cursor: not-allowed;
        }

        .join-button:hover {
            background-color: #2980b9;
        }

        #success-message {
            display: none;
            padding: 20px;
            background-color: #2ecc71;
            color: white;
            margin-top: 20px;
            border-radius: 5px;
        }
    </style>
</head>

<body>

  

    <div class="tournament-grid">
    </div>

    <div id="success-message">
        <p>Tournament joined successfully!</p>
        <button onclick="window.location.href='user_dashboard.php'">Back to Dashboard</button>
    </div>

    <script>
        function joinTournament(tournamentId) {
            console.log(tournamentId);

            if (confirm('Are you sure you want to join this tournament?')) {
                fetch('../Controllers/JoinTournamentController.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded', 
                        },
                        body: 'tournament_id=' + tournamentId 
                    })
                    .then(response => response.json()) 
                    .then(data => {
                        if (data.success) {
                           
                            document.getElementById('success-message').style.display = 'block';
                            document.querySelector('.tournament-grid').style.display = 'none';
                        } else {
                            alert(data.message);
                        }
                    })
                    .catch(error => {
                        alert('Error: ' + error); 
                    });
            }
        }
    </script>

</body>

</html>
