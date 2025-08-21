<!DOCTYPE html>
<html>
<head>
    <title>Return a Book</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        select, button { padding: 10px; margin: 5px 0; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Return a Book</h1>
    
    <form method="POST">
        <div>
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
    </form>
    
    <div id="borrows-container">
        <!-- Borrowed books will be displayed here -->
    </div>

    <script>
    $(document).ready(function() {
        $('#user_id').change(function() {
            const userId = $(this).val();
            if (userId) {
                $.get('/borrow/user-borrows?user_id=' + userId, function(data) {
                    let html = '';
                    if (data.length > 0) {
                        html = `
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Book</th>
                                    <th>Borrow Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                        `;
                        
                        data.forEach(borrow => {
                            const borrowDate = new Date(borrow.borrow_date).toLocaleDateString();
                            
                            html += `
                            <tr>
                                <td>${borrow.id}</td>
                                <td>${borrow.title}</td>
                                <td>${borrowDate}</td>
                                <td>
                                    <form method="POST" action="/borrow/return">
                                        <input type="hidden" name="borrow_id" value="${borrow.id}">
                                        <button type="submit">Return</button>
                                    </form>
                                </td>
                            </tr>
                            `;
                        });
                        
                        html += </tbody></table>;
                    } else {
                        html = '<p>No active borrows for this user.</p>';
                    }
                    $('#borrows-container').html(html);
                });
            } else {
                $('#borrows-container').html('');
            }
        });
    });
    </script>
</body>
</html>