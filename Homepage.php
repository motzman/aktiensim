<?php
session_start();
include 'dbconnect.php';

// --- Aktie speichern ---
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST["name"];
    $preis = $_POST["preis"];
    $details = $_POST["details"];

    $stmt = $conn->prepare("INSERT INTO aktien (name, preis, details) VALUES (?, ?, ?)");
    $stmt->bind_param("sds", $name, $preis, $details);
    $stmt->execute();
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Aktiensimulator</title>
<style>
    body {
        margin: 0;
        padding: 60px 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        background: #0205b2; /* Blau als Hintergrund */
        color: #ff8000;
        font-family: Arial, sans-serif;
    }

    .login_button {
        position: fixed;
        top: 20px;
        right: 20px;
        background-color: #ff8000;
        color: white;
        border: none;
        cursor: pointer;
        font-weight: bold;
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 14px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.3);
        transition: all 0.3s ease;
    }

    .login_button:hover {
        background-color: #ff5555;
        transform: scale(1.05);
    }

    h1 {
        color: white;
        background-color: #1c2ea5;
        padding: 15px 25px;
        border-radius: 12px;
        text-align: center;
        box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        margin-bottom: 40px;
    }

    .chart {
        position: relative;
        width: 600px;
        height: 400px;
    }

    .axis {
        position: absolute;
        background: #003366;
    }

    .axis.x {
        bottom: 0;
        left: 0;
        width: 100%;
        height: 12px;
        border-radius: 6px;
    }

    .axis.y {
        bottom: 0;
        left: 0;
        width: 12px;
        height: 100%;
        border-radius: 6px;
    }

    .line {
        position: absolute;
        bottom: 12px;
        left: 12px;
        width: calc(100% - 12px);
        height: calc(100% - 12px);
    }

    .line svg {
        width: 100%;
        height: 100%;
    }
</style>
</head>
<body>

<button onclick="window.location.href='login.php'" class="login_button"> Login</button>
<h1>Aktiensimulator</h1>

<div class="chart">
  <div class="axis x"></div>
  <div class="axis y"></div>
  <div class="line">
    <svg viewBox="0 0 300 200" fill="none" stroke-width="10" stroke-linecap="round" stroke-linejoin="round">
      <polyline points="0,150 60,60 120,130 180,70 240,100 300,20" stroke="#ff4b3e" fill="none"/>
      <polyline points="0,180 60,100 120,140 180,90 240,130 300,40" stroke="#fcd34d" fill="none"/>
      <polyline points="0,160 60,90 120,110 180,70 240,90 300,20" stroke="#22c55e" fill="none"/>
      <polyline points="0,180 60,100 120,120 180,80 240,100 300,10" stroke="#3b82f6" fill="none"/>
      <polygon points="280,30 300,10 290,40" fill="#3b82f6"/>
    </svg>
  </div>
</div>

</body>
</html>
