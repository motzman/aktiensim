<?php
session_start();

include 'dbconnect.php';

if (!isset($_SESSION["id"])) {
    die("Error: you must be logged in");
}
$userId = $_SESSION['id'];
$username = $_SESSION['username'];

try {
    $sql = "SELECT us.quantity,
     s.name AS stock_name,
      s.price_per_share FROM user_stocks us   
      INNER JOIN stocks s ON us.stock_id = s.id
      WHERE us.user_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $stmt = mysqli_prepare($conn, "SELECT balance FROM users WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);

    $userResult = mysqli_stmt_get_result($stmt);
    $userRow = $userResult->fetch_assoc();
    $balance = $userRow["balance"];


    $totalPortfolio = 0;
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Your Portfolio</title>
</head>
<link rel="stylesheet" href="./styles/dashboard.css">
<body>
    <div class="card">
        <h2>Willkommen zum Portfolio, <?php echo $username; ?></h2>
        <table>
            <tr>
                <th>Aktien Name</th>
                <th>Aktien</th>
                <th>Preis pro Aktie</th>
                <th>Gesamtwert</th>
            </tr>
            <?php
            while ($row = $result->fetch_assoc()) {
                $stockName = $row['stock_name'];
                $quantity = $row['quantity'];
                $pricePerShare = $row['price_per_share'];
                $stockValue = $quantity * $pricePerShare;
                $totalPortfolio += $stockValue;
            ?>
                <tr>
                    <td><?php echo htmlspecialchars($stockName); ?></td>
                    <td><?php echo $quantity; ?></td>
                    <td><?php echo number_format($pricePerShare, 2); ?> €</td>
                    <td><?php echo number_format($stockValue, 2); ?> €</td>
                </tr>
            <?php } ?>
        </table>

        <div class="total">
            <strong>Gesamtportfoliowert:</strong>
            <?php echo number_format($totalPortfolio, 2); ?> €
        </div>
        <div class="total">
            <strong>Ihr Kontostand:</strong>
            <?php echo number_format($balance, 2); ?> €
        </div>
        <div>
            <button onclick="window.location.href='index.php'" class="btnBack">Back</button>
        </div>
        <div>
            <button onclick="window.location.href='logout.php'" class="logout">Logout </button>
        </div>
    </div>
</body>

</html>