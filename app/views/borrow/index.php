<!DOCTYPE html>
<html>
<head>
    <title>Borrow a Book</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        h1 {
            color: #2c3e50;
            margin-top: 0;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            font-size: 16px;
        }
        
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }
        
        select, input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            box-sizing: border-box;
        }
        
        select:focus, input:focus {
            border-color: #3498db;
            outline: none;
            box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.2);
        }
        
        button {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 12px 20px;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        button:hover {
            background-color: #2980b9;
        }
        
        .book-details {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 4px;
            border-left: 4px solid #3498db;
            margin-top: 5px;
        }
        
        .book-title {
            font-weight: 600;
            color: #2c3e50;
        }
        
        .book-info {
            color: #7f8c8d;
            font-size: 14px;
        }
        
        .action-links {
            margin-top: 25px;
            display: flex;
            gap: 15px;
        }
        
        .action-links a {
            color: #3498db;
            text-decoration: none;
            padding: 8px 15px;
            border: 1px solid #3498db;
            border-radius: 4px;
            transition: all 0.3s;
        }
        
        .action-links a:hover {
            background-color: #3498db;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Borrow a Book</h1>
        
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-error">
                <?= htmlspecialchars($_GET['error']) ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($_GET['success']) ?>
            </div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="user_id">Select User:</label>
                <select id="user_id" name="user_id" required>
                    <option value="">-- Select a user --</option>
                    <?php foreach ($users as $user): ?>
                        <option value="<?= $user['id'] ?>">
                            <?= htmlspecialchars($user['name'] . ' (' . $user['email'] . ')') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="book_id">Select Book:</label>
                <select id="book_id" name="book_id" required>
                    <option value="">-- Select a book --</option>
                    <?php foreach ($books as $book): ?>
                        <?php if ($book['copies'] > 0): ?>
                            <option value="<?= $book['id'] ?>" 
                                data-author="<?= htmlspecialchars($book['author']) ?>"
                                data-copies="<?= $book['copies'] ?>">
                                <?= htmlspecialchars($book['title']) ?>
                            </option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
                
                <div id="book-details" class="book-details" style="display: none;">
                    <div class="book-title" id="book-title"></div>
                    <div class="book-info">
                        Author: <span id="book-author"></span> | 
                        Available Copies: <span id="book-copies"></span>
                    </div>
                </div>
            </div>
            
            <button type="submit">Borrow Book</button>
        </form>
        
        <div class="action-links">
            <a href="/books">Manage Books</a>
            <a href="/users">Manage Users</a>
            <a href="/borrow/return">Return Books</a>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const bookSelect = document.getElementById('book_id');
            const bookDetails = document.getElementById('book-details');
            const bookTitle = document.getElementById('book-title');
            const bookAuthor = document.getElementById('book-author');
            const bookCopies = document.getElementById('book-copies');
            
            bookSelect.addEventListener('change', function() {
                if (this.value) {
                    const selectedOption = this.options[this.selectedIndex];
                    bookTitle.textContent = selectedOption.text;
                    bookAuthor.textContent = selectedOption.dataset.author;
                    bookCopies.textContent = selectedOption.dataset.copies;
                    bookDetails.style.display = 'block';
                } else {
                    bookDetails.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>