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
    <title>Aktien erstellen</title>
</head>
<link rel="stylesheet" href="./styles/create.css">
<body>

    <button onclick="window.location.href='index.php'" class="logout_button"> Aktien</button>

    <h1>Aktie hinzufügen</h1>

    <form method="POST" action="">
        <label for="name">Aktienname:</label>
        <input type="text" id="name" name="name" required>

        <label for="preis">Preis pro Aktie (€):</label>
        <input type="number" id="preis" name="price_per_share" step="0.01" required>

        <label>Gesamtanzahl der Aktien:</label>
        <input type="number" name="total_shares" required>

        <button type="submit" class="bg-red-500 p-2 rounded-lg">Aktie erstellen</button>
    </form>
</body>

</html>