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
    <style>
        body {
            background: linear-gradient(135deg, #0205b2, #4f58ff);
            margin: 0;
            padding: 50px 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            font-family: Arial, sans-serif;
            color: #fff;
        }

        .logout_button {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #ff3333;
            color: white;
            border: none;
            cursor: pointer;
            font-weight: bold;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 14px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
        }

        .logout_button:hover {
            background-color: #ff5555;
            transform: scale(1.05);
        }

          .dashboard {
            position: fixed;
            top: 20px;
            left: 20px;
            background-color: #ff3333;
            color: white;
            border: none;
            cursor: pointer;
            font-weight: bold;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 14px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
        }
        h1 {
            margin-bottom: 30px;
            text-align: center;
            color: #fff;
            font-size: 2rem;
        }

        form {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            padding: 30px;
            border-radius: 16px;
            width: 100%;
            max-width: 450px;
            display: flex;
            flex-direction: column;
            gap: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(5px);
        }

        label {
            font-weight: 600;
            margin-bottom: 4px;
        }

        input {
            width: 100%;
            padding: 10px 12px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 15px;
            outline: none;
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
        }

        input:focus {
            border-color: #00ffea;
            box-shadow: 0 0 5px rgba(0, 255, 234, 0.5);
            background: rgba(255, 255, 255, 0.3);
        }

        button.button_create {
            background-color: #ff8000;
            color: white;
            border: none;
            padding: 12px;
            font-size: 16px;
            border-radius: 10px;
            cursor: pointer;
            font-weight: bold;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }

        button.button_create:hover {
            background-color: #ff9900;
            transform: translateY(-2px);
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
        }

        h2 {
            margin-top: 40px;
            margin-bottom: 20px;
            font-size: 1.5rem;
        }

        table {
            width: 100%;
            max-width: 900px;
            border-collapse: collapse;
            margin-bottom: 50px;
        }

        th,
        td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        th {
            background-color: rgba(255, 255, 255, 0.2);
            color: #fff;
        }

        tr:nth-child(even) {
            background-color: rgba(255, 255, 255, 0.05);
        }
    </style>
</head>

<body>

    <button onclick="window.location.href='Create.php'" class="logout_button"> Aktie Hinzufügen</button>
    <button onclick="window.location.href='login.php'" class="bg-red-500 hover:bg-red-300 p-2 rounded-lg w-lg cursor-pointer">Login </button>
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
                    <td><a href='buy_form.php?stock_id=" . htmlspecialchars($row['id']) . "' class='p-1 font-bold text-white'>Buy</a></td>
                 <td><a href='sell_form.php?stock_id=" . htmlspecialchars($row['id']) . "' class='p-1 font-bold text-white'>Sell</a></td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>Noch keine Aktien gespeichert.</td></tr>";
            }
            ?>
    </table>

</body>

</html>