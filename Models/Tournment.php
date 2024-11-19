<?php
require_once __DIR__ . '/../Config/Database.php';

class Tournament
{
    private $conn;
    private $table = 'tournaments';

    public $id;
    public $tournament_name;
    public $tournament_date;
    public $tournament_id;
    public $venue;
    public $max_participants;
    public $status;
    public $name;
    public $date;
    public $description;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getAllTournaments()
    {
        $query = "SELECT 
                    t.*,
                    COALESCE(COUNT(DISTINCT tr.user_id), 0) as current_participants
                  FROM 
                    tournaments t
                  LEFT JOIN 
                    tournament_registrations tr ON t.id = tr.tournament_id
                  GROUP BY 
                    t.id
                  ORDER BY 
                    t.tournament_date DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function createTournament()
    {
        // Query to insert a new tournament
        $query = "INSERT INTO " . $this->table . " (tournament_name, tournament_date, tournament_description)
                  VALUES (:tournament_name, :tournament_date, :tournament_description)";

        // Prepare the query
        $stmt = $this->conn->prepare($query);

        // Bind parameters
        $stmt->bindParam(':tournament_name', $this->name);
        $stmt->bindParam(':tournament_date', $this->date);
        $stmt->bindParam(':tournament_description', $this->description);

        // Execute the query and return whether it was successful
        return $stmt->execute();
    }

    public function getParticipants($tournament_id)
    {
        $query = "SELECT u.username, u.email, p.joined_at 
        FROM participants p 
        JOIN users u ON p.user_id = u.id 
        WHERE p.tournament_id = :tournament_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':tournament_id', $tournament_id);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTournamentById($tournament_id)
    {
        $query = "SELECT * FROM tournaments WHERE id = :tournament_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':tournament_id', $tournament_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function joinTournament($user_id, $tournament_id)
    {
        $checkQuery = "SELECT COUNT(*) FROM tournament_registrations 
        WHERE user_id = :user_id AND tournament_id = :tournament_id";
        $stmt = $this->conn->prepare($checkQuery);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':tournament_id', $tournament_id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->fetchColumn() > 0) {
            return false;
        }

        $capacityQuery = "SELECT 
             t.max_participants,
             COUNT(tr.user_id) as current_count
           FROM tournaments t
           LEFT JOIN tournament_registrations tr ON t.id = tr.tournament_id
           WHERE t.id = :tournament_id
           GROUP BY t.id";

        $stmt = $this->conn->prepare($capacityQuery);
        $stmt->bindParam(':tournament_id', $tournament_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result && $result['current_count'] >= $result['max_participants']) {
            return false;
        }

        $query = "INSERT INTO tournament_registrations (user_id, tournament_id, created_at) 
   VALUES (:user_id, :tournament_id, NOW())";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':tournament_id', $tournament_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return true; 
        } else {
            
            $errorInfo = $stmt->errorInfo();
            echo "Error: " . $errorInfo[2]; 
            return false; 
        }
    }
    public function getAvailableTournaments()
    {
        $query = "SELECT 
                    t.*,
                    COALESCE(COUNT(DISTINCT tr.user_id), 0) as current_participants
                  FROM 
                    tournaments t
                  LEFT JOIN 
                    tournament_registrations tr ON t.id = tr.tournament_id
            
                  GROUP BY 
                    t.id
                  ORDER BY 
                    t.tournament_date DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
