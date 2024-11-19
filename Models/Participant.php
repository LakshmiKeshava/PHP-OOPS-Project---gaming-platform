<?php
require_once __DIR__ . '/../Config/Database.php';

class Participant {
    private $conn;
    private $table = 'tournament_participants';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllParticipants() {
        $query = "SELECT 
                    u.username as participant_name,
                    u.email,
                    t.tournament_name,
                    t.tournament_date,
                    tp.id as registration_id
                FROM 
                    tournament_participants tp
                JOIN 
                    users u ON tp.user_id = u.id
                JOIN 
                    tournaments t ON tp.tournament_id = t.id
                ORDER BY 
                    t.tournament_date DESC,
                    u.username ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getParticipantsByTournamentId($tournament_id) {
        $query = "SELECT 
                    u.username as name, 
                    u.email, 
                    tp.id as registration_id
                FROM 
                    tournament_participants tp
                JOIN 
                    users u ON tp.user_id = u.id
                WHERE 
                    tp.tournament_id = :tournament_id
                ORDER BY 
                    u.username ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':tournament_id', $tournament_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addParticipant($user_id, $tournament_id) {
        if ($this->isUserRegistered($user_id, $tournament_id)) {
            return false;
        }

        $query = "INSERT INTO " . $this->table . " 
                    (user_id, tournament_id) 
                VALUES 
                    (:user_id, :tournament_id)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':tournament_id', $tournament_id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    private function isUserRegistered($user_id, $tournament_id) {
        $query = "SELECT id FROM " . $this->table . " 
                WHERE user_id = :user_id 
                AND tournament_id = :tournament_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':tournament_id', $tournament_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }
}
?>