<!DOCTYPE html>
<html>
<head>
    <title>Add New Book</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input[type="text"], input[type="number"] { padding: 8px; width: 300px; }
        button { padding: 10px 20px; background: #4CAF50; color: white; border: none; cursor: pointer; }
    </style>
</head>
<body>
    <h1>Add New Book</h1>
    
    <form method="POST">
        <div class="form-group">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" required>
        </div>
        
        <div class="form-group">
            <label for="author">Author:</label>
            <input type="text" id="author" name="author" required>
        </div>
        
        <div class="form-group">
            <label for="copies">Number of Copies:</label>
            <input type="number" id="copies" name="copies" min="1" required>
        </div>
        
        <button type="submit">Add Book</button>
        <a href="/books" style="margin-left: 10px;">Cancel</a>
    </form>
</body>
</html>