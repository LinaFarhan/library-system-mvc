<?php
namespace App\Models;

use App\Config\Database;
use App\Models\Traits\LoggingTrait;

class User {
    use LoggingTrait;
    
    private $db;

    public function __construct() {
        $this->db = (new Database())->connect();
    }

    public function getAll(): array {
        $stmt = $this->db->query("SELECT * FROM users");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function create(array $data): void {
        $stmt = $this->db->prepare(
            "INSERT INTO users (name, email) 
            VALUES (:name, :email)"
        );
        $stmt->execute($data);
        $this->logAction("User created: {$data['email']}");
    }

    public function update(int $id, array $data): void {
        $stmt = $this->db->prepare(
            "UPDATE users SET 
            name = :name, 
            email = :email 
            WHERE id = :id"
        );
        $data['id'] = $id;
        $stmt->execute($data);
        $this->logAction("User updated: ID $id");
    }

    public function delete(int $id): void {
        try {
            $this->db->beginTransaction();
            
            // Delete associated borrows first
            $stmt = $this->db->prepare(
                "DELETE FROM borrows WHERE user_id = :id"
            );
            $stmt->execute(['id' => $id]);
            
            // Then delete the user
            $stmt = $this->db->prepare(
                "DELETE FROM users WHERE id = :id"
            );
            $stmt->execute(['id' => $id]);
            
            $this->db->commit();
            $this->logAction("User deleted: ID $id");
        } catch (\PDOException $e) {
            $this->db->rollBack();
            error_log("User deletion failed: " . $e->getMessage());
        }
    }

    public function getUserById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $user ?: null;
    }
}