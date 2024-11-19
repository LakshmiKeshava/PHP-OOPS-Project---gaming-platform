<?php
require_once '../Config/Database.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $database = new Database();
    $conn = $database->getConnection();

    // Delete tournament
    $sql = "DELETE FROM tournaments WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        echo "<p class='message success'>Tournament deleted successfully.</p>";
    } else {
        echo "<p class='message error'>Failed to delete tournament.</p>";
    }
    echo "<a href='admin_dashboard.php'><button class='back-button'>Go Back to Dashboard</button></a>";
} else {
    echo "<p class='message error'>Invalid tournament ID.</p>";
    echo "<a href='admin_dashboard.php'><button class='back-button'>Go Back to Dashboard</button></a>";
}
?>
