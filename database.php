<?php

class Database {
    private $host;
    private $dbName;
    private $username;
    private $password;
    private $charset;
    private $pdo;

    public function __construct() {
        
        $this->host = 'localhost'; 
        $this->dbName = 'webi'; 
        $this->username = 'root'; 
        $this->password = ''; 
        $this->charset = 'utf8mb4';
        $this->connect();
    }

    
    private function connect() {
        $dsn = "mysql:host={$this->host};dbname={$this->dbName};charset={$this->charset}";

        try {
            $this->pdo = new PDO($dsn, $this->username, $this->password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    
    public function getConnection() {
        return $this->pdo;
    }
}

?>
