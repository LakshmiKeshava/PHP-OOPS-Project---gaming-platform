<?php
require_once '../Config/Database.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $database = new Database();
    $conn = $database->getConnection();

    // Fetch tournament details
    $sql = "SELECT * FROM tournaments WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $tournament = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$tournament) {
        echo "<div class='message-container'>";
        echo "<p class='message error'>Tournament not found.</p>";
        echo "<a href='admin_dashboard.php' class='back-button'>Go Back to Dashboard</a>";
        echo "</div>";
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST['tournament_name'];
        $date = $_POST['tournament_date'];
        $venue = $_POST['venue'];
        $maxParticipants = $_POST['max_participants'];
        $description = $_POST['description'];

        // Update the tournament
        $sql = "UPDATE tournaments SET tournament_name = :name, tournament_date = :date, venue = :venue, 
                max_participants = :maxParticipants, description = :description WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':venue', $venue);
        $stmt->bindParam(':maxParticipants', $maxParticipants);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            // Success message
            echo "<div class='message-container'>";
            echo "<p class='message success'>Tournament edited successfully.</p>";
            echo "<a href='admin_dashboard.php' class='back-button'>Go Back to Dashboard</a>";
            echo "</div>";
            exit;
        } else {
            // Failure message
            echo "<div class='message-container'>";
            echo "<p class='message error'>Failed to update tournament.</p>";
            echo "<a href='admin_dashboard.php' class='back-button'>Go Back to Dashboard</a>";
            echo "</div>";
        }
    }
} else {
    echo "<div class='message-container'>";
    echo "<p class='message error'>Invalid tournament ID.</p>";
    echo "<a href='admin_dashboard.php' class='back-button'>Go Back to Dashboard</a>";
    echo "</div>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Assets/CSS/admin_style.css">
    <title>Edit Tournament</title>
</head>
<body>
    <div class="main-content">
        <h2>Edit Tournament</h2>
        <form method="post">
            <label for="tournament_name">Tournament Name</label>
            <input type="text" id="tournament_name" name="tournament_name" value="<?php echo htmlspecialchars($tournament['tournament_name']); ?>" required>

            <label for="tournament_date">Date</label>
            <input type="date" id="tournament_date" name="tournament_date" value="<?php echo htmlspecialchars($tournament['tournament_date']); ?>" required>

            <label for="venue">Venue</label>
            <input type="text" id="venue" name="venue" value="<?php echo htmlspecialchars($tournament['venue']); ?>" required>

            <label for="max_participants">Max Participants</label>
            <input type="number" id="max_participants" name="max_participants" value="<?php echo htmlspecialchars($tournament['max_participants']); ?>" required>

            <label for="description">Description</label>
            <textarea id="description" name="description" required><?php echo htmlspecialchars($tournament['description']); ?></textarea>

            <button type="submit">Update Tournament</button>
        </form>
    </div>
</body>
</html>
