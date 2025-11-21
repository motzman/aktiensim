<?php
session_start();
include 'dbconnect.php'; 

if (!isset($_SESSION['id'])) {
    die("Error: You must be logged in to sell stocks.");
}
$userId = $_SESSION['id'];


$stockId = (int)($_POST['stock_id'] ?? 0);
$quantity = (int)($_POST['quantity'] ?? 0);

if ($stockId <= 0 || $quantity <= 0) {
    die("Error: Invalid stock ID or quantity. Quantity must be greater than 0.");
}


try {
    mysqli_begin_transaction($conn);
    $stmt = mysqli_prepare($conn, "SELECT quantity FROM user_stocks WHERE user_id = ? AND stock_id = ? FOR UPDATE");
    mysqli_stmt_bind_param($stmt, "ii", $userId, $stockId);
    mysqli_stmt_execute($stmt);
    $owned = mysqli_stmt_get_result($stmt)->fetch_assoc();

    if(!$owned || $owned['quantity']< $quantity){
        throw new Exception("Error, Not enough shares to sell");
    }

    $ownedQuantity = $owned['quantity'];
    $stmt = mysqli_prepare($conn, "SELECT price_per_share FROM stocks WHERE id = ? FOR UPDATE");
    mysqli_stmt_bind_param($stmt, "i",$stockId);
    mysqli_stmt_execute($stmt);
    $stock = mysqli_stmt_get_result($stmt)->fetch_assoc();

    if(!$stock){
        throw new Exception("Error, no stock found");
    }

    $totalValue = $stock['price_per_share'] * $quantity;
    $newPrice = $stock['price_per_share'] * 0.9;
    $newOwnedQuantity = $ownedQuantity - $quantity;

     
    $stmt = mysqli_prepare($conn, "UPDATE users SET balance = balance + ? WHERE id = ?");;
    mysqli_stmt_bind_param($stmt, "di", $totalValue, $userId);
    mysqli_stmt_execute($stmt);
    
    $stmt = mysqli_prepare($conn, "UPDATE stocks SET available_shares = available_shares + ?, price_per_share = ? WHERE id = ? ");
    mysqli_stmt_bind_param($stmt, "idi", $quantity, $newPrice, $stockId);
    mysqli_stmt_execute($stmt);

    if($newOwnedQuantity == 0){
        $stmt = mysqli_prepare($conn, "DELETE FROM user_stocks WHERE user_id = ? AND stock_id = ?");
        mysqli_stmt_bind_param($stmt, "ii", $userId, $stockId);
    } else{
        $stmt = mysqli_prepare($conn, "UPDATE user_stocks SET quantity = ? WHERE user_id = ? AND stock_id = ?");
        mysqli_stmt_bind_param($stmt, "iii", $newOwnedQuantity, $userId, $stockId);
    }
    mysqli_stmt_execute($stmt);
    mysqli_commit($conn);
    echo "Sold shares, $quantity ";
    echo "<a href='index.php'>Go back</a>";
} catch (Exception $e) {
    mysqli_rollback($conn);
    echo "Transaction failed: " . $e->getMessage();
}

mysqli_close($conn);
