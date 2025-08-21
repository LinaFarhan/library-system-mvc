<?php
namespace App\Models\Traits;

use App\Config\Database;

trait SearchableTrait {
    public function searchBooks(string $term): array {
        $db = (new Database())->connect();
        $stmt = $db->prepare(
            "SELECT * FROM books 
            WHERE title LIKE :term OR author LIKE :term"
        );
        $searchTerm = "%$term%";
        $stmt->bindParam(':term', $searchTerm);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}