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

     // Fonction pour récupérer les événements d'un utilisateur spécifique
     public function getUserEvents($userId)
     {
         // Connexion à la base de données
         $pdo = Database::getConnection();
 
         // Préparation de la requête SQL pour récupérer les événements de l'utilisateur
         $stmt = $pdo->prepare("SELECT * FROM events WHERE user_id = :user_id");
         $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
         
         // Exécution de la requête
         $stmt->execute();
 
         // Récupération des résultats
         return $stmt->fetchAll(PDO::FETCH_ASSOC);
     }
    
    
}
