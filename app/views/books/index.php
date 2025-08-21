<!DOCTYPE html>
<html>
<head>
    <title>Books</title>
</head>
<body>
    <h1>Book Management</h1>
    <a href="/books/create">Add New Book</a>
   <form action="/books" method="get">
        <input type="hidden" name="action" value="search"/>
        <input type="text" name="query" placeholder="Search books..."/>
        <button type="submit">Search</button>
    </form>
    
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Author</th>
            <th>Copies</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($books as $book): ?>
        <tr>
            <td><?= htmlspecialchars($book['id'] ?? '') ?></td>
            <td><?= htmlspecialchars($book['title'] ?? '') ?></td>
            <td><?= htmlspecialchars($book['author'] ?? '') ?></td>
            <td><?= htmlspecialchars($book['copies'] ?? '') ?></td>
            <td>
                <a href="/books/edit/<?= $book['id'] ?>">Edit</a>
                <a href="/books/delete/<?= $book['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>