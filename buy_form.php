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
    <title>Kaufe <?php echo htmlspecialchars($name); ?></title>
</head>
 <link rel="stylesheet" href="./styles/forms.css">
<body>
<div class="container">
        <h2>Aktien Kaufen</h2>
        
        <div class="stock-details">
            <p><strong>Stock:</strong> <?php echo htmlspecialchars($name); ?></p>
            <p><strong>Price per Share:</strong> €<?php echo htmlspecialchars(number_format($price, 2)); ?></p>
        </div>
        
        <form action="buy.php" method="POST">
            <input type="hidden" name="stock_id" value="<?php echo $stock_id; ?>">

            <label for="quantity">Quantity (Anzahl):</label>
            <input type="number" id="quantity" name="quantity" placeholder="z.b., 10" required min="1">

            <button type="submit">Buy <?php echo htmlspecialchars($name); ?></button>
        </form>
        <div>
    <a href="index.php" class="go-back-link">Return to Home</a>
</div>
</body>
</html>

