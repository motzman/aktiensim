<?php
session_start();
include 'dbconnect.php';


if (count($_POST) > 0) {
    $name =  $_POST['name'];
    $password = $_POST['password'];
    $sql = "SELECT * FROM users WHERE name = '$name'";
    $result = mysqli_query($conn, $sql);
    $num  = mysqli_num_rows($result);

    if ($num == 1) {
        while ($row = mysqli_fetch_assoc($result)) {
            if (password_verify($password, $row["password"])) {

                $_SESSION['username'] = $name;
                $_SESSION['id'] = $row['id'];

                header("Location: index.php");
                exit;
            } else {
                echo "Error";
            }
        }
    } else {
        echo "Error";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log in</title>
    <style>
    </style>
</head>

<body>
    <div>
        <div>
            <button onclick="window.location.href='index.php'">X</button>
        </div>
        <div>
            <h1>Sign in</h1>

            <form method="POST">
                <input type="name" name="name" placeholder="Your Name" required>
                <input type="password" name="password" placeholder="Your Password" required>
                <div class="flex gap-4">
                    <button type="submit">Sign in</button>
                    <button type="button" onclick="window.location.href='signup.php'"> Sign up </button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>