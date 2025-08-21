<?php
namespace App\Controllers;

use App\Models\Book;
use App\Models\User;
use App\Models\Borrow;

class BorrowController {
    private $bookModel;
    private $userModel;
    private $borrowModel;

    public function __construct() {
        $this->bookModel = new Book();
        $this->userModel = new User();
        $this->borrowModel = new Borrow();
    }

    public function borrow(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $bookId = (int)$_POST['book_id'];
            $userId = (int)$_POST['user_id'];
            $this->borrowModel->borrowBook($bookId, $userId);
            header('Location: /books');
        } else {
            $books = $this->bookModel->getAll();
            $users = $this->userModel->getAll();
            require _DIR_ . '/../../views/borrow/borrow.php';
        }
    }
}