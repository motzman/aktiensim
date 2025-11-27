<?php
session_start();
include 'dbconnect.php';

if (!isset($_SESSION['id'])) {
    die("Error: You must be logged in to buy stocks.");
}
$userId = $_SESSION['id'];
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
    $stmt = mysqli_prepare($conn, "UPDATE users SET balance = balance - ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "di", $totalCost, $userId);
    mysqli_stmt_execute($stmt);


    $stmt = mysqli_prepare($conn, "UPDATE stocks SET available_shares = available_shares - ?, price_per_share = price_per_share * 2 WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "ii", $quantity, $stockId);
    mysqli_stmt_execute($stmt);

    $stmt = mysqli_prepare($conn, "SELECT quantity FROM user_stocks WHERE user_id = ? AND stock_id = ?");
    mysqli_stmt_bind_param($stmt, "ii", $userId, $stockId);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $owned = mysqli_fetch_assoc($result);

    mysqli_stmt_close($stmt);


    if ($owned) {
        $stmt = mysqli_prepare($conn, "UPDATE user_stocks SET quantity = quantity + ? WHERE user_id = ? AND stock_id = ?");
        mysqli_stmt_bind_param($stmt, "iii", $quantity, $userId, $stockId);
        mysqli_stmt_execute($stmt);
    } else {
        $stmt = mysqli_prepare($conn, "INSERT INTO user_stocks (user_id, stock_id, quantity) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "iii", $userId, $stockId, $quantity);
        mysqli_stmt_execute($stmt);
    }
    mysqli_stmt_close($stmt);

    mysqli_commit($conn);
    echo "Bought shares, $quantity ";
    echo "<a href='index.php'>Go back</a>";
} catch (Exception $e) {
    mysqli_rollback($conn);
    echo "Transaction failed: " . $e->getMessage();
}

mysqli_close($conn);
