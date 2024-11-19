<?php
session_start();
require_once '../Config/Database.php';  // Include your Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Create database instance
        $database = new Database();
        $conn = $database->getConnection();

        // Get form data
        $tournament_name = $_POST['tournament_name'];
        $tournament_date = $_POST['tournament_date'];
        $venue = $_POST['venue'];
        $max_participants = $_POST['max_participants'];

        // Prepare SQL statement to insert tournament into the database
        $sql = "INSERT INTO tournaments (tournament_name, tournament_date, venue, max_participants) 
                VALUES (:tournament_name, :tournament_date, :venue, :max_participants)";
        
        $stmt = $conn->prepare($sql);
        
        // Bind parameters to the SQL query
        $stmt->bindParam(':tournament_name', $tournament_name);
        $stmt->bindParam(':tournament_date', $tournament_date);
        $stmt->bindParam(':venue', $venue);
        $stmt->bindParam(':max_participants', $max_participants);
        
        // Execute the query
        if ($stmt->execute()) {
            // If the tournament is successfully inserted
            $_SESSION['success_message'] = "Tournament successfully hosted!";
            header("Location: host_success.php");  // Redirect to success page
            exit();
        } else {
            // If the insertion fails
            $_SESSION['error_message'] = "Error hosting tournament.";
            header("Location: host_tournament.php");  // Redirect back to the form with an error message
            exit();
        }
    } catch(PDOException $e) {
        // In case of any database connection or query error
        $_SESSION['error_message'] = "Error: " . $e->getMessage();
        header("Location: host_tournament.php");  // Redirect back to the form with an error message
        exit();
    }
}
?>
