<?php
require_once '../Config/Database.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $database = new Database();
    $db = $database->getConnection();

    $tournamentId = $_POST['tournament_id'];
    $userId = $_SESSION['user_id']; // Assuming you have user ID in session

    // Insert participant into the database
    $sql = "INSERT INTO participants (tournament_id, user_id) VALUES (:tournament_id, :user_id)";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':tournament_id', $tournamentId);
    $stmt->bindParam(':user_id', $userId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Could not join the tournament.']);
    }
}
?>