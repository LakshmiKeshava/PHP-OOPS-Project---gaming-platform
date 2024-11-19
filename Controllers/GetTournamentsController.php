<?php
session_start();
require_once '../Models/Tournament.php';
require_once '../Config/Database.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'You must be logged in']);
    exit;
}

// Get database connection
$database = new Database();
$db = $database->getConnection();
$tournament = new Tournament($db);

// Get available tournaments
$tournaments = $tournament->getAvailableTournaments();

// Respond with tournament data
echo json_encode([
    'success' => true,
    'tournaments' => $tournaments
]);
?>
