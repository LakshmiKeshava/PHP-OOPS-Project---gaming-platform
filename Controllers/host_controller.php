<?php
session_start();

// Debugging - Check if form data is received
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<pre>";
    print_r($_POST); // This will show the form data
    echo "</pre>";

    // Check if all required fields are filled
    $tournamentName = $_POST['tournament_name'] ?? '';
    $tournamentDate = $_POST['tournament_date'] ?? '';
    $tournamentDescription = $_POST['tournament_description'] ?? '';

    if (empty($tournamentName) || empty($tournamentDate) || empty($tournamentDescription)) {
        $_SESSION['error_message'] = "All fields are required!";
        header("Location: ../views/host_tournament.php");
        exit();
    }

    // Database Connection
    require_once __DIR__ . '/../Config/Database.php';
    require_once __DIR__ . '/../Models/Tournment.php';

    $database = new Database();
    $db = $database->getConnection();
    $tournament = new Tournament($db);

    // Prepare data to be inserted
    $tournament->name = $tournamentName;
    $tournament->date = $tournamentDate;
    $tournament->description = $tournamentDescription;

    // Insert into database
    try {
        $result = $tournament->createTournament(); // Assuming createTournament is the method to insert
        if ($result) {
            $_SESSION['success_message'] = "Tournament hosted successfully!";
        } else {
            $_SESSION['error_message'] = "Failed to host the tournament.";
        }
    } catch (Exception $e) {
        $_SESSION['error_message'] = "Error: " . $e->getMessage();
    }

    // Redirect back to the form page
    header("Location: ../views/host_tournament.php");
    exit();
} else {
    echo "No POST request received"; // Debugging line to show if the form wasn't submitted
}
