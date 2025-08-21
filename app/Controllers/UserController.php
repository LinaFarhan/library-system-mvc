<?php
namespace App\Controllers;

use App\Models\User;

class UserController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function index(): void {
        $users = $this->userModel->getAll();
        require _DIR_ . '/../../views/users/index.php';
    }

    public function create(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'],
                'email' => $_POST['email']
            ];
            $this->userModel->create($data);
            header('Location: /users');
            exit;
        }
        require _DIR_ . '/../../views/users/create.php';
    }

    public function edit(int $id): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'],
                'email' => $_POST['email']
            ];
            $this->userModel->update($id, $data);
            header('Location: /users');
            exit;
        }
        
        $user = $this->userModel->getUserById($id);
        if (!$user) {
            header('Location: /users');
            exit;
        }
        
        require _DIR_ . '/../../views/users/edit.php';
    }

    public function delete(int $id): void {
        $this->userModel->delete($id);
        header('Location: /users');
        exit;
    }
}