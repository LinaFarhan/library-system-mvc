<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
</head>
<body>
    <h1>Edit User</h1>
    
    <form method="POST">
        <div>
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" 
                   value="<?= htmlspecialchars($user['name']) ?>" required>
        </div>
        <div>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" 
                   value="<?= htmlspecialchars($user['email']) ?>" required>
        </div>
        <button type="submit">Update User</button>
    </form>
    
    <p><a href="/users">Back to Users</a></p>
</body>
</html>