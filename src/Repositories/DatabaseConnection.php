<?php
namespace LibraryManagementSystem\Repositories;

class DatabaseConnection {
    private static ?DatabaseConnection $instance = null;
    private \PDO $connection;
    
    private function __construct() {
        $host = 'localhost';
        $dbname = 'library_db';
        $username = 'root';
        $password = '';
        
        try {
            $this->connection = new \PDO(
                "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
                $username,
                $password,
                [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
                ]
            );
        } catch (\PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }
    
    public static function getInstance(): DatabaseConnection {
        if (self::$instance === null) {
            self::$instance = new DatabaseConnection();
        }
        return self::$instance;
    }
    
    public function getConnection(): \PDO {
        return $this->connection;
    }
}