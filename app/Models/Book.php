<?php
namespace App\Models;

use App\Config\Database;
use App\Models\Traits\LoggingTrait;
use App\Models\Traits\SearchableTrait;

class Book {
    use SearchableTrait, LoggingTrait;
    
    private $db;

    public function __construct() {
        $this->db = (new Database())->connect();
    }

    public function getAll(): array {
        $stmt = $this->db->query("SELECT * FROM books");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM books WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
    }

    public function create(array $data): bool {
        try {
            $stmt = $this->db->prepare(
                "INSERT INTO books (title, author, copies) 
                VALUES (:title, :author, :copies)"
            );
            $stmt->execute($data);
            $this->logAction("Book created: {$data['title']}");
            return true;
        } catch (\PDOException $e) {
            error_log("Book creation failed: " . $e->getMessage());
            return false;
        }
    }

    public function update(int $id, array $data): bool {
        try {
            $stmt = $this->db->prepare(
                "UPDATE books SET 
                title = :title, 
                author = :author, 
                copies = :copies 
                WHERE id = :id"
            );
            $data['id'] = $id;
            $stmt->execute($data);
            $this->logAction("Book updated: ID $id");
            return true;
        } catch (\PDOException $e) {
            error_log("Book update failed: " . $e->getMessage());
            return false;
        }
    }

    public function delete(int $id): bool {
        try {
            $stmt = $this->db->prepare("DELETE FROM books WHERE id = :id");
            $stmt->execute(['id' => $id]);
            $this->logAction("Book deleted: ID $id");
            return true;
        } catch (\PDOException $e) {
            error_log("Book deletion failed: " . $e->getMessage());
            return false;
        }
    }

    public function increaseCopies(int $id): void {
        try {
            $stmt = $this->db->prepare(
                "UPDATE books SET copies = copies + 1 WHERE id = :id"
            );
            $stmt->execute(['id' => $id]);
        } catch (\PDOException $e) {
            error_log("Failed to increase copies: " . $e->getMessage());
        }
    }
}