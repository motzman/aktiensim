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
        body {
            margin: 0;
            padding: 0;
            background: #1e3a8a; /* Blau */
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: white;
        }

        .container {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(6px);
            padding: 30px 40px;
            border-radius: 12px;
            width: 350px;
            text-align: center;
        }

        .close-btn {
            background: darkgoldenrod;
            border: none;
            font-size: 14px;
            color: white;
            cursor: pointer;
            color: white;
            padding: 6px;
        }

        input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 6px;
            border: none;
            outline: none;
        }

        button {
            padding: 12px 18px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            background-color: #ff8000; /* Orange */
            color: white;
            font-weight: bold;
            margin-top: 10px;
        }

        .flex {
            display: flex;
            gap: 12px;
            justify-content: center;
        }

        .error {
            color: #ffcccc;
            background: rgba(255, 0, 0, 0.2);
            padding: 8px;
            border-radius: 6px;
            margin-bottom: 12px;
        }
    </style>
</head>

<body>
<link rel="stylesheet" href="./styles/signup.css">
    <div class="container">
        <h1>Login</h1>

        <?php if (!empty($error_message)): ?>
            <p class="error"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <form method="POST">
            <input type="text" name="username" placeholder="Your Username" required>
            <input type="password" name="password" placeholder="Password" required>

            <div class="flex">
                <button type="submit" class="primary-btn">Login</button>
                <button type="button" class="secondary-btn" onclick="window.location.href='login.php'">Sign up</button>
            </div>
        </form>
        
        <div>
            <a href="index.php" class="go-back-link">Return to Home</a>
        </div>
    </div>
</body>


</html>
