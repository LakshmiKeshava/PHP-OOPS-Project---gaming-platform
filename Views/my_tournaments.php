<?php
session_start();


if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit;
}


require_once '../Config/Database.php';


$database = new Database();
$conn = $database->getConnection();

$user_id = $_SESSION['user_id']; 


$query = "SELECT t.id, t.tournament_name, t.tournament_date, t.venue, t.max_participants, t.current_participants, t.status
          FROM tournaments t
          JOIN participants p ON t.id = p.tournament_id
          WHERE p.user_id = :user_id";

$stmt = $conn->prepare($query);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();


if ($stmt->rowCount() > 0) {
    $tournaments = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $tournaments = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Tournaments</title>
    
    <style>
        /* Basic Styles */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f6f9;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Header */
        h1 {
            font-size: 32px;
            color: #2c3e50;
            text-align: center;
            margin-bottom: 20px;
        }

        /* Back to Dashboard Button */
        .back-btn {
            display: inline-block;
            background-color: #3498db;
            color: white;
            padding: 10px 20px;
            font-size: 16px;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 20px;
            transition: background-color 0.3s;
        }

        .back-btn:hover {
            background-color: #2980b9;
        }

        /* Tournament List */
        .tournament-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .tournament-card {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .tournament-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .tournament-card h2 {
            font-size: 24px;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .tournament-card p {
            font-size: 16px;
            color: #7f8c8d;
            margin-bottom: 10px;
        }

        .tournament-card p strong {
            color: #2c3e50;
        }

        .status-open {
            display: inline-block;
            background-color: #2ecc71;
            color: white;
            padding: 5px 10px;
            border-radius: 15px;
        }

        .status-closed {
            display: inline-block;
            background-color: #e74c3c;
            color: white;
            padding: 5px 10px;
            border-radius: 15px;
        }

        /* No Tournaments Found */
        p.no-tournaments {
            text-align: center;
            font-size: 18px;
            color: #7f8c8d;
        }

        /* Media Queries for Responsiveness */
        @media (max-width: 768px) {
            .tournament-list {
                grid-template-columns: 1fr;
            }

            h1 {
                font-size: 28px;
            }

            .tournament-card h2 {
                font-size: 22px;
            }

            .tournament-card p {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Back to Dashboard Button -->
        <a href="user_dashboard.php" class="back-btn">Back to Dashboard</a>
        
        <h1>My Tournaments</h1>

        <?php if (empty($tournaments)): ?>
            <p>No tournaments found. You haven't joined any tournaments yet.</p>
        <?php else: ?>
            <div class="tournament-list">
                <?php foreach ($tournaments as $tournament): ?>
                    <div class="tournament-card">
                        <h2><?php echo htmlspecialchars($tournament['tournament_name']); ?></h2>
                        <p><strong>Date:</strong> <?php echo htmlspecialchars($tournament['tournament_date']); ?></p>
                        <p><strong>Venue:</strong> <?php echo htmlspecialchars($tournament['venue']); ?></p>
                        <p><strong>Participants:</strong> <?php echo htmlspecialchars($tournament['current_participants']) . '/' . htmlspecialchars($tournament['max_participants']); ?></p>
                        <p><strong>Status:</strong> 
                            <span class="<?php echo $tournament['status'] === 'open' ? 'status-open' : 'status-closed'; ?>">
                                <?php echo ucfirst(htmlspecialchars($tournament['status'])); ?>
                            </span>
                        </p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
