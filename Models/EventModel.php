<?php
// EventModel.php
require_once '../database.php';

class EventModel {
    private $db;

    public function __construct() {
        $this->db = new Database();  // Connexion à la base de données
    }
    public function addEvent($title, $description, $date, $time, $location, $category, $image) {
        // Vérifier si l'ID de l'utilisateur est disponible dans la session
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];  // Récupérer l'ID de l'utilisateur de la session
        } else {
            // Si l'utilisateur n'est pas connecté, renvoyer false ou une erreur
            return false;
        }

        // Préparer la requête d'insertion avec le champ user_id
        $query = "INSERT INTO events (title, description, date, time, location, category, image, user_id) 
                  VALUES (:title, :description, :date, :time, :location, :category, :image, :user_id)";
        
        // Préparer la requête à l'aide de PDO
        $stmt = $this->db->getConnection()->prepare($query);

        // Lier les paramètres
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':time', $time);
        $stmt->bindParam(':location', $location);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':image', $image);
        $stmt->bindParam(':user_id', $user_id);  // Lier l'ID de l'utilisateur

        // Exécution de la requête et vérification du succès
        if ($stmt->execute()) {
            return true;  // L'événement a été ajouté avec succès
        } else {
            return false;  // L'ajout de l'événement a échoué
        }
    }
}
?>
