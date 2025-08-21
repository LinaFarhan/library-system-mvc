<?php
// public/index.php

// تفعيل عرض الأخطاء للتطوير
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// التحميل التلقائي للكلاسات
spl_autoload_register(function ($class) {
    $file = __DIR__ . '/../app/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

// الحصول على المسار المطلوب
$request_uri = $_SERVER['REQUEST_URI'];
$base_path = '/librarysystem/public';

// إزالة المسار الأساسي إذا كان موجوداً
if (strpos($request_uri, $base_path) === 0) {
    $request_uri = substr($request_uri, strlen($base_path));
}

// تنظيف المسار
$request = parse_url($request_uri, PHP_URL_PATH);
$request = rtrim($request, '/');

// إذا كان الطلب فارغاً، توجيه إلى الصفحة الرئيسية
if ($request === '') {
    $request = '/';
}

// توجيه الطلبات
switch ($request) {
    case '/':
    case '/books':
        $controller = new App\Controllers\BookController();
        $controller->index();
        break;
        
    case '/books/create':
        $controller = new App\Controllers\BookController();
        $controller->create();
        break;
        
    case preg_match('#^/books/edit/(\d+)$#', $request, $matches) ? $request : '':
        $controller = new App\Controllers\BookController();
        $controller->edit((int)$matches[1]);
        break;
        
    case preg_match('#^/books/delete/(\d+)$#', $request, $matches) ? $request : '':
        $controller = new App\Controllers\BookController();
        $controller->delete((int)$matches[1]);
        break;
        
    case '/users':
        $controller = new App\Controllers\UserController();
        $controller->index();
        break;
        
    case '/users/create':
        $controller = new App\Controllers\UserController();
        $controller->create();
        break;
        
    case preg_match('#^/users/edit/(\d+)$#', $request, $matches) ? $request : '':
        $controller = new App\Controllers\UserController();
        $controller->edit((int)$matches[1]);
        break;
        
    case preg_match('#^/users/delete/(\d+)$#', $request, $matches) ? $request : '':
        $controller = new App\Controllers\UserController();
        $controller->delete((int)$matches[1]);
        break;
        
    case '/borrow':
        $controller = new App\Controllers\BorrowController();
        $controller->borrow();
        break;
        
    case '/borrow/return':
        $controller = new App\Controllers\BorrowController();
        $controller->returnBook();
        break;
        
    case '/borrow/user-borrows':
        $controller = new App\Controllers\BorrowController();
        $controller->getUserBorrows();
        break;
        
    case '/search':
        $controller = new App\Controllers\BookController();
        $controller->search();
        break;
        
    default:
        http_response_code(404);
        echo 'Page not found - Request: ' . htmlspecialchars($request);
        break;
}