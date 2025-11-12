<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    include 'dbconnect.php';
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "Select * from users where username = '$username'";
    $result = mysqli_query($conn, $sql);
    $num = mysqli_num_rows($result);

    if ($num == 0) {
            $hash = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO users ( username, password)
                    VALUES ('$username', '$hash')";

            $result = mysqli_query($conn, $sql);

            if ($result) {
                $id = mysqli_insert_id($conn);

                $_SESSION['username'] = $username;
                $_SESSION['id'] = $id;

                header("Location: index.php");
                exit;
            }
        }
    } 
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign up</title>
    <style>
    </style>
</head>

<body>
    <div>
        <div>
            <span></span>
            <button onclick="window.location.href='index.php'">X</button>
        </div>

        <div>
            <h1>Sign up</h1>
            <form method="POST">
                <input type="text" name="username" placeholder="Your username" required>
                <input type="password" name="password" placeholder="Your Password" required>
                <div class="flex gap-4">
                    <button type="submit">Sign up</button>
                    <button type="button" onclick="window.location.href='signin.php'"> Sign in </button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>