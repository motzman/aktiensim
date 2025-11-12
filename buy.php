<?php

include 'dbconnect.php';


$userId = $_POST['user_id'];
$stockId = $_POST['stock_id'];
$quantity = $_POST['quantity'];

$sql = "SELECT price_per_share, available_shares FROM stocks WHERE id = $stockId";
$result = mysqli_query($conn, $sql);
$stock = mysqli_fetch_assoc($result);

if (!$stock) {
    die("Error, stock not found");
}

if ($stock["available_shares"] < $quantity) {
    die("Error, not enough shares available");
}


$totalCost = $stock['price_per_share'] * $quantity;


$sql = "SELECT balance FROM users WHERE id = $userId";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);

if ($user['balance'] < $totalCost) {
    die("Not enough money");
}


mysqli_begin_transaction($conn);

try {
    $sql = "UPDATE users SET balance = balance - $totalCost WHERE id = $userId";
    mysqli_query($conn, $sql);

    $sql = "UPDATE stocks SET available_shares = available_shares - $quantity, price_per_share = price_per_share * 1.01 WHERE id = $stockId";
    mysqli_query($conn, $sql);

    $sql = "SELECT quantity FROM user_stocks WHERE user_id = $userId AND stock_id = $stockId";
    $result = mysqli_query($conn, $sql);
    $owned = mysqli_fetch_assoc($result);

    if ($owned) {
        $sql = "UPDATE user_stocks SET quantity = quanitiy + $quantity WHERE user_id = $userId AND stock_id = $stockId";
        mysqli_query($conn, $sql);
    } else {
        $sql = "INSERT INTO user_stocks (user_id, stock_id, quantity) VALUES ($userId, $stockId, $quantity)";
        mysqli_query($conn, $sql);
    }

    mysqli_commit($conn);
    echo "Bought shares, $quantity";
    echo "<a href='buy_form.php'>Go back</a>";
} catch (Exception $e) {
    mysqli_rollback($conn);
    echo "Transaction failed: " . $e->getMessage();
}

mysqli_close($conn);
