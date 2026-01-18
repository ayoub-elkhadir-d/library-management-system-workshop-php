<?php
namespace LibraryManagementSystem\Repositories;

class DatabaseConnection
{
    private static $instance = null;
    private $connection;

    private function __construct()
    {
        $host = 'localhost';
        $dbname = 'library_management';
        $username = 'root';
        $password = '';

        try {
            $this->connection = new \PDO(
                "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
                $username,
                $password
            );
            $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            exit();
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new DatabaseConnection();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->connection;
    }
}