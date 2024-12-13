<?php

require_once '../../database.php'; 

class personneModel {
    private $db;

    public function __construct() {
        $this->db = new Database();  // Connexion à la base de données
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

    // Récupérer l'ID de l'utilisateur par son email
    public function getUserIdByEmail($email) {
        $stmt = $this->db->getConnection()->prepare("SELECT id FROM personnes WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        // Retourne l'ID de l'utilisateur, ou null si l'utilisateur n'existe pas
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['id'] : null;
    }
}

?>
