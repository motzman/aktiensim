<?php 
session_start();
include  'dbconnect.php';

if(!isset($_SESSION["id"])){
    die("Error: you must be logged in");
}

$userId = $_SESSION['id'];
$stock_id = (int)($_GET['stock_id'] ?? 0);
$quantity = (int)($_POST['quantity'] ?? 0);
if ($stock_id <= 0){
    die("Error invalid stock id");
}

$name = "N/A";
$price = 0.00;

try{
    $stmt = $conn->prepare("SELECT name, price_per_share FROM stocks WHERE id = ?");
    $stmt->bind_param("i", $stock_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if($stock = $result->fetch_assoc()){
        $name = $stock["name"];
        $price = $stock["price_per_share"];
    }else{
        die("Stock not found");
    }
    $stmt->close();
}catch(Exception $e){
    die("Error: " . $e->getMessage());
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buy <?php echo htmlspecialchars($name); ?></title>
    <style>
        body { font-family: Arial; margin: 50px; }
        input, button { margin: 5px 0; padding: 8px; }
        form { max-width: 300px; border: 1px solid #ccc; padding: 20px; border-radius: 8px; }
    </style>
</head>
<body>
     <h2>Buy Stock</h2>
     
     <form action="buy.php" method="POST">
        <div>
            <h4>Name: <?php echo htmlspecialchars($name); ?></h4>
            <h4>Price per Share: €<?php echo htmlspecialchars($price); ?></h4>
        </div>
     
        <input type="hidden" name="stock_id" value="<?php echo $stock_id; ?>">

        <label for="quantity">Quantity:</label><br>
        <input type="number" id="quantity" name="quantity" required min="1"><br>

        <button type="submit">Buy</button>
    </form>
</body>
</html>