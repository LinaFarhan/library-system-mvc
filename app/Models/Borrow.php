<?php
namespace App\Models;

use App\Config\Database;
use App\Models\Traits\LoggingTrait;
use App\Models\Notification\NotificationInterface;
use App\Models\Notification\EmailNotification;
use \DateTime;

class Borrow {
    use LoggingTrait;
    
    private $db;

    public function __construct() {
        $this->db = (new Database())->connect();
    }

    public function borrowBook(int $bookId, int $userId): void {
        try {
            $this->db->beginTransaction();
            
            // 1. Reduce book quantity
            $stmt = $this->db->prepare(
                "UPDATE books SET copies = copies - 1 WHERE id = :id"
            );
            $stmt->execute(['id' => $bookId]);
            
            // 2. Create borrow record
            $stmt = $this->db->prepare(
                "INSERT INTO borrows (book_id, user_id, borrow_date) 
                VALUES (:book_id, :user_id, NOW())"
            );
            $stmt->execute([
                'book_id' => $bookId,
                'user_id' => $userId
            ]);
            
            $borrowId = $this->db->lastInsertId();
            
            // 3. Send notification
            $notification = new EmailNotification();
            $notification->send("Book borrowed: ID $bookId by user ID $userId");
            
            $this->db->commit();
            $this->logAction("Book borrowed: $bookId by $userId");
        } catch(\Exception $e) {
            $this->db->rollBack();
            error_log("Borrow failed: " . $e->getMessage());
            throw $e;
        }
    }

    public function returnBook(int $borrowId): void {
        try {
            $this->db->beginTransaction();
            
            // Update return date
            $stmt = $this->db->prepare(
                "UPDATE borrows SET return_date = NOW() WHERE id = :id"
            );
            $stmt->execute(['id' => $borrowId]);
            
            $this->db->commit();
            $this->logAction("Book returned for borrow ID: $borrowId");
        } catch(\Exception $e) {
            $this->db->rollBack();
            error_log("Return failed: " . $e->getMessage());
            throw $e;
        }
    }

    public function calculateLateFee(int $borrowId): float {
        $stmt = $this->db->prepare(
            "SELECT borrow_date FROM borrows WHERE id = :id"
        );
        $stmt->execute(['id' => $borrowId]);
        $borrow = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        if (!$borrow || !$borrow['borrow_date']) {
            return 0;
        }
        
        $dueDate = new DateTime($borrow['borrow_date']);
        $dueDate->modify('+14 days');
        $today = new DateTime();
        
        if ($today > $dueDate) {
            $diff = $today->diff($dueDate);
            return $diff->days * 0.50; // $0.50 per day
        }
        return 0;
    }
    
    public function getBorrowsByUser(int $userId): array {
        $stmt = $this->db->prepare(
            "SELECT b.*, books.title 
             FROM borrows b
             JOIN books ON b.book_id = books.id
             WHERE user_id = :user_id AND return_date IS NULL"
        );
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    public function getBorrowById(int $id): ?array {
        $stmt = $this->db->prepare(
            "SELECT * FROM borrows WHERE id = :id"
        );
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
    }
}