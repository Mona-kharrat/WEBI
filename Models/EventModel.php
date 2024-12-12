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
    public function registerUserForEvent($eventId, $userId)
    {
        $query = "UPDATE events SET inscri = 1 WHERE id = :eventId AND user_id = :userId";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':eventId', $eventId);
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();
    }
    

    public function getAllEvents()
    {
        if (!isset($_SESSION['user']['id'])) {
            return []; // Pas d'événements si non connecté
        }

        $stmt = $this->db->prepare("SELECT * FROM events");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function handleImageUpload($image)
    {
        if ($image && $image['error'] === UPLOAD_ERR_OK) {
            $allowedExtensions = ['png', 'jpg', 'jpeg'];
            $fileInfo = pathinfo($image['name']);
            $fileExtension = strtolower($fileInfo['extension']);

            if (in_array($fileExtension, $allowedExtensions)) {
                $uploadDir = '../uploads/events/';
                $uploadFile = $uploadDir . basename($image['name']);
                move_uploaded_file($image['tmp_name'], $uploadFile);
                return $uploadFile;
            } else {
                throw new Exception('Seules les images PNG, JPG et JPEG sont autorisées.');
            }
        } else {
            throw new Exception('Une erreur est survenue lors du téléchargement de l\'image.');
        }
    }
}
?>
