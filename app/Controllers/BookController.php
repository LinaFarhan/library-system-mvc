<?php
namespace App\Controllers;

use App\Models\Book;

class BookController {
    private $bookModel;

    public function __construct() {
        $this->bookModel = new Book();
    }

    public function index(): void {
        $books = $this->bookModel->getAll();
        require __DIR__ . '/../../views/books/index.php';
    }

    public function create(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'title' => $_POST['title'],
                'author' => $_POST['author'],
                'copies' => (int)$_POST['copies']
            ];
            $this->bookModel->create($data);
            header('Location: /books');
        } else {
            require __DIR__ . '/../../views/books/create.php';
        }
    }

public function edit(int $id): void {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = [
            'title' => $_POST['title'],
            'author' => $_POST['author'],
            'copies' => (int)$_POST['copies']
        ];
        $this->bookModel->update($id, $data);
        header('Location: /books');
        exit;
    } else {
        $book = $this->bookModel->getById($id);
        if (!$book) {
            header('Location: /books');
            exit;
        }
        require _DIR_ . '/../../views/books/edit.php';
    }
}

    public function delete(int $id): void {
        $this->bookModel->delete($id);
        header('Location: /books');
        exit;
    }

   public function search(): void {
    if (isset($_GET['query'])) {
        $results = $this->bookModel->searchBooks($_GET['query']);
        // هنا يمكنك عرض النتائج أو إعادتها كـ JSON
        echo json_encode($results);
        exit;
    }
    require _DIR_ . '/../../views/books/search.php';
}
}