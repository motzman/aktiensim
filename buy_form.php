<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buy Stock</title>
    <style>
        body { font-family: Arial; margin: 50px; }
        input, button { margin: 5px 0; padding: 8px; }
        form { max-width: 300px; }
    </style>
</head>
<body>
     <h2>Buy Stock</h2>
    <form action="buy.php" method="POST">
        <label>User ID:</label><br>
        <input type="number" name="user_id" required><br>

        <label>Stock ID:</label><br>
        <input type="number" name="stock_id" required><br>

        <label>Quantity:</label><br>
        <input type="number" name="quantity" required><br>

        <button type="submit">Buy</button>
    </form>
</body>
</html>