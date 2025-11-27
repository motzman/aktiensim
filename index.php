<?php
session_start();
include  'dbconnect.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST["name"];
    $price = $_POST["price_per_share"];
    $shares = $_POST["total_shares"];
    $creator_id = $_SESSION["id"];

    $stmt = $conn->prepare("
        INSERT INTO stocks (name, creator_id, total_shares, available_shares, price_per_share)
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("siiid", $name, $creator_id, $shares, $shares, $price);
    $stmt->execute();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title> Aktien</title>
</head>
<link rel="stylesheet" href="./styles/index.css">
<body>

    <button onclick="window.location.href='Create.php'" class="addBtn"> Aktie Hinzufügen</button>
    <?php
    $loggedIn = isset($_SESSION['id']);
    $username = $loggedIn ? $_SESSION['username'] : '';
    ?>
    <?php if ($loggedIn): ?>
        <button class="bg-red-500 hover:bg-red-700 p-2 rounded-lg w-xs cursor-pointer">
            Willkommen zurück <?php echo $username; ?>
        </button>
    <?php else: ?>
        <button onclick="window.location.href='login.php'" class="bg-red-500 hover:bg-red-700 p-2 rounded-lg w-xs cursor-pointer">
            Login
        </button>
    <?php endif; ?>

    <button onclick="window.location.href='dashboard.php'" class="dashboard"> Portfolio</button>

    <h1> Aktien</h1>

    <table>
        <tr>
            <th>Nr.</th>
            <th>Name</th>
            <th>Preis (€)</th>
            <th>Verfügbare Shares</th>
            <th>Erstellt am</th>
            <th>Kaufen</th>
            <th>Verkaufen</th>
            <?php
            $result = $conn->query("SELECT * FROM stocks ORDER BY created_at DESC");

            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['name']}</td>
                    <td>{$row['price_per_share']}</td>
                    <td>{$row['available_shares']}</td>
                    <td>{$row['created_at']}</td>
                    <td><a href='buy_form.php?stock_id=" . htmlspecialchars($row['id']) . "' class='p-1 font-bold text-white'>Kaufen</a></td>
                 <td><a href='sell_form.php?stock_id=" . htmlspecialchars($row['id']) . "' class='p-1 font-bold text-white'>Verkaufen</a></td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>Noch keine Aktien gespeichert.</td></tr>";
            }
            ?>
    </table>

</body>

</html>