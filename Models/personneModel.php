<?php

require_once realpath(__DIR__ . '/../database.php');

class personneModel {
    private $db;

    public function __construct() {
        $this->db = new Database();  // Connexion à la base de données
    }

    public function updateUser($role, $userId) {
        $stmt = $this->db->getConnection()->prepare("UPDATE personnes SET role = ? WHERE id = ?");
        if (!$stmt->execute([$role, $userId])) {
            throw new Exception("Erreur lors de la mise à jour : " . implode(", ", $stmt->errorInfo()));
        }
    }
     /////////////////password ////////////
     public function resetPassword($email, $newPassword) {
        // Récupérer l'utilisateur par son email
        $user = $this->getUserByEmail($email);
        if (!$user) {
            throw new Exception("Aucun utilisateur trouvé avec cet email.");
        }
    
        // Hachage du nouveau mot de passe
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    
        // Préparer la requête de mise à jour du mot de passe
        $stmt = $this->db->getConnection()->prepare(
            "UPDATE personnes SET password = :password WHERE email = :email"
        );
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':email', $email);
    
        // Exécuter la requête
        return $stmt->execute();
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
    public function getTotalUsersCount() 
{
    $stmt = $this->db->getConnection()->prepare("SELECT COUNT(*) FROM personnes");
    $stmt->execute();
    return $stmt->fetchColumn();
}
        public function getUsers($limit = 10, $offset = 0) {
             // Calculez la limite et le décalage pour paginati
        
             if (!isset($_SESSION['user']['id'])) {
                return []; // Pas d'événements si non connecté
            }
        
            $stmt = $this->db->getConnection()->prepare(
                "SELECT * FROM personnes 
                 ORDER BY id DESC 
                 LIMIT :limit OFFSET :offset"
            );
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        
    // Insertion d'un utilisateur
    public function insertUser($username, $email, $password) {
        // Préparer la requête d'insertion
        $stmt = $this->db->getConnection()->prepare(
            "INSERT INTO personnes (username, email, password, role) 
             VALUES (:username, :email, :password, 'user')"
        );

        // Lier les paramètres
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);

        // Hachage du mot de passe avant insertion
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt->bindParam(':password', $hashedPassword);
        
        // Exécuter la requête
        return $stmt->execute();  // Retourne true si l'exécution a réussi, false sinon
    }

    // Vérifier si un utilisateur existe déjà avec l'email
    public function userExists($email) {
        $stmt = $this->db->getConnection()->prepare("SELECT * FROM personnes WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch() !== false; // Retourne true si l'utilisateur existe
    }

    // Récupérer un utilisateur par son email
    public function getUserByEmail($email) {
        $stmt = $this->db->getConnection()->prepare("SELECT * FROM personnes WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);  // Retourne les informations de l'utilisateur sous forme de tableau associatif
    }
    public function getUserById($userId) {
        $stmt = $this->db->getConnection()->prepare("SELECT * FROM personnes WHERE id = :id");
        $stmt->bindParam(':id', $userId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC); 
    }

    // Récupérer l'ID de l'utilisateur par son email
    public function getUserIdByEmail($email) {
        $stmt = $this->db->getConnection()->prepare("SELECT id FROM personnes WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        // Retourne l'ID de l'utilisateur, ou null si l'utilisateur n'existe pas
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['id'] : null;
    }
    public function blockUser($userId) {
        // Préparer la requête pour bloquer l'utilisateur
        $stmt = $this->db->getConnection()->prepare(
            "UPDATE personnes SET status = 1 WHERE id = :id"
        );
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        
        // Exécuter la requête et retourner le résultat
        return $stmt->execute();
    }
    public function unblockUser($userId) {
        // Préparer la requête pour débloquer l'utilisateur
        $stmt = $this->db->getConnection()->prepare(
            "UPDATE personnes SET status = 0 WHERE id = :id"
        );
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        
        // Exécuter la requête et retourner le résultat
        return $stmt->execute();
    }
    public function deleteUser($userId) {
        // Préparer la requête pour débloquer l'utilisateur
        $stmt = $this->db->getConnection()->prepare(
            "DELETE FROM personnes WHERE id = :id"
        );
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        
        // Exécuter la requête et retourner le résultat
        return $stmt->execute();
    }
    public function updateUserProfile($userId, $username, $email) {
        $stmt = $this->db->getConnection()->prepare(
            "UPDATE personnes SET username = :username, email = :email WHERE id = :id"
        );
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getEventCountByUser($userId) {
        $stmt = $this->db->getConnection()->prepare(
            "SELECT COUNT(*) FROM events WHERE user_id = :userId"
        );
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    
    
    public function getEventRegistrationsCount($userId) {
        $stmt = $this->db->getConnection()->prepare(
            "SELECT COUNT(*) FROM user_events WHERE user_id = :userId"
        );
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    
        
}

?>
