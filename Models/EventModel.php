<?php

require_once realpath(__DIR__ . '/../database.php');



class EventModel
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }
    public function deleteEventById($eventId, $userId)
    {
        try {
            // Préparer la requête de suppression
            $query = "DELETE FROM events WHERE id = :id AND user_id = :user_id";
            $stmt = $this->db->prepare($query);

            // Lier les paramètres
            $stmt->bindParam(':id', $eventId, PDO::PARAM_INT);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);

            // Exécuter la requête
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erreur lors de la suppression de l'événement : " . $e->getMessage());
            return false;
        }
    }
    public function deleteEvent($eventId)
    {
        try {
            // Préparer la requête de suppression
            $query = "DELETE FROM events WHERE id = :id";
            $stmt = $this->db->prepare($query);

            // Lier les paramètres
            $stmt->bindParam(':id', $eventId, PDO::PARAM_INT);

            // Exécuter la requête
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erreur lors de la suppression de l'événement : " . $e->getMessage());
            return false;
        }
    }
    public function getEventById($id)
    {
        try {
            $query = "SELECT * FROM events WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération de l'événement : " . $e->getMessage());
            return false;
        }
    }
    
    
    public function createEvent($title, $description, $date, $time, $location, $category, $image)
    {
        if (!isset($_SESSION['user']['id'])) {
            throw new Exception("Utilisateur non connecté.");
        }

        $userId = $_SESSION['user']['id'];

        $query = "INSERT INTO events (title, description, date, time, location, category, image, user_id, inscri) 
                  VALUES (:title, :description, :date, :time, :location, :category, :image, :user_id, 0)";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            ':title' => $title,
            ':description' => $description,
            ':date' => $date,
            ':time' => $time,
            ':location' => $location,
            ':category' => $category,
            ':image' => $image,
            ':user_id' => $userId
        ]);
    }
  
    public function getUserEvents($userId)
    {
        if (!isset($_SESSION['user']['id'])) {
            return []; // Pas d'événements si non connecté
        }
    
        $query = "SELECT * FROM events WHERE user_id = :userId";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getUserRegisteredEvents($userId)
{
    $query = "
        SELECT e.* 
        FROM events e
        INNER JOIN user_events ue ON e.id = ue.event_id
        WHERE ue.user_id = :userId
    ";
    $stmt = $this->db->prepare($query);
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


public function registerUserForEvent($userId, $eventId)
{
    // Vérifier si l'utilisateur est déjà inscrit
    $checkQuery = "SELECT COUNT(*) FROM user_events WHERE user_id = :userId AND event_id = :eventId";
    $stmt = $this->db->prepare($checkQuery);
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->bindParam(':eventId', $eventId, PDO::PARAM_INT);
    $stmt->execute();
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        // Si l'utilisateur est déjà inscrit, retourner un message d'erreur ou une valeur
        return false; // L'utilisateur est déjà inscrit
    }

    // Insérer l'utilisateur dans la table d'inscription
    $query = "INSERT INTO user_events (user_id, event_id) VALUES (:userId, :eventId)";
    $stmt = $this->db->prepare($query);
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->bindParam(':eventId', $eventId, PDO::PARAM_INT);
    
    return $stmt->execute(); 
}


public function isEventExists($eventId)
{
    $query = "SELECT COUNT(*) FROM events WHERE id = :eventId";
    $stmt = $this->db->prepare($query);
    $stmt->bindParam(':eventId', $eventId);
    $stmt->execute();
    return $stmt->fetchColumn() > 0;
}
public function updateEvent($title, $date, $location,$eventId,$userId) {
    $stmt = $this->db->prepare("UPDATE events SET title = ?, date = ?, location = ? WHERE id = ? AND user_id = ?");
    return $stmt->execute([$title, $date, $location, $eventId, $userId]);
}
//nav pagination
public function getAllEvents($page = 1, $limit = 6, $location = null, $date = null) 
{
    // Calculez la limite et le décalage pour la pagination
    $offset = ($page - 1) * $limit;

    if (!isset($_SESSION['user']['id'])) {
        return []; // Pas d'événements si non connecté
    }

    // Construire la requête de base
    $sql = "SELECT * FROM events WHERE 1=1";

    // Ajouter des conditions de filtre si des valeurs sont passées
    if ($location) {
        $sql .= " AND location LIKE :location";
    }

    if ($date) {
        $sql .= " AND date = :date";
    }

    // Ajouter la pagination à la requête
    $sql .= " LIMIT :limit OFFSET :offset";

    // Préparer la requête
    $stmt = $this->db->prepare($sql);

    // Lier les paramètres de filtre si nécessaires
    if ($location) {
        $stmt->bindValue(':location', '%' . $location . '%');
    }

    if ($date) {
        $stmt->bindValue(':date', $date);
    }

    // Lier les paramètres de pagination
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);

    // Exécuter la requête
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//nav pagination

public function getTotalEvents() 
{
    $stmt = $this->db->prepare("SELECT COUNT(*) FROM events");
    $stmt->execute();
    return $stmt->fetchColumn();
}


    
}
?>
