<?php
namespace App\Config;

class Database {
    private $host = 'localhost';
    private $dbname = 'library';
    private $username = 'root';
    private $password = '';
    private $conn;

     public function __construct() {
        $dotenv = Dotenv::createImmutable(_DIR_ . '/../../');
        $dotenv->load();

        $this->host = $_ENV['DB_HOST'] ?? 'localhost';
        $this->dbname = $_ENV['DB_NAME'] ?? 'library';
        $this->username = $_ENV['DB_USER'] ?? 'root';
        $this->password = $_ENV['DB_PASS'] ?? '';
    }

    public function connect() {
        $this->conn = null;
        try {
            $this->conn = new \PDO(
                "mysql:host={$this->host};dbname={$this->dbname}",
                $this->username,
                $this->password,
                [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch(\PDOException $e) {
            error_log("Database Error: " . $e->getMessage());
        }
        return $this->conn;
    }
}