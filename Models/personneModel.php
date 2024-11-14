<?php

require_once '../database.php'; 


class UserModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function insertUser($username, $email, $password) {
        $stmt = $this->db->getConnection()->prepare("INSERT INTO personnes (username, email, password, role) VALUES (:username, :email, :password, 'user')");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        
        // Hachage du mot de passe
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt->bindParam(':password', $hashedPassword);
        
        return $stmt->execute();
    }

    public function userExists($email) {
        $stmt = $this->db->getConnection()->prepare("SELECT * FROM personnes WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch() !== false; // Retourne true si l'utilisateur existe
    }
}

?>
