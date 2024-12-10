<?php
require_once '../database.php';

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
        $imagePath = $this->handleImageUpload($image);

        $query = "INSERT INTO events (title, description, date, time, location, category, image, user_id) 
                  VALUES (:title, :description, :date, :time, :location, :category, :image, :user_id)";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            ':title' => $title,
            ':description' => $description,
            ':date' => $date,
            ':time' => $time,
            ':location' => $location,
            ':category' => $category,
            ':image' => $imagePath,
            ':user_id' => $userId
        ]);
    }

    public function getUserEvents($userId)
{
    echo "Recherche des événements pour l'utilisateur avec ID: " . $userId; // Déboguer l'ID de l'utilisateur

    $query = "SELECT id, title, date, location FROM events WHERE user_id = :user_id";
    $stmt = $this->db->prepare($query);
    $stmt->execute([':user_id' => $userId]);

    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
    var_dump($events); // Affiche les événements récupérés
    return $events; 
}    private function handleImageUpload($image)
    {
        if ($image && $image['error'] === UPLOAD_ERR_OK) {
            $imagePath = 'uploads/' . basename($image['name']);
            move_uploaded_file($image['tmp_name'], $imagePath);
            return $imagePath;
        }
        return null;
    }
}
