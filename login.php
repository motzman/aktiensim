<?php
session_start();
include 'dbconnect.php';

$error_message = '';


if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (isset($_POST['username'], $_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();

            if (password_verify($password, $row["password"])) {
                $_SESSION['username'] = $row['username'];
                $_SESSION['id'] = $row['id'];

                header("Location: index.php");
                exit;
            } else {
                $error_message = "Invalid username or password.";
            }
        } else {
            $error_message = "Invalid username or password.";
        }
        $stmt->close();
    } else {
        $error_message = "Please fill out all fields.";
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
                <input type="text" name="username" placeholder="Your Name" required>
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