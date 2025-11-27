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
</head>
<link rel="stylesheet" href="./styles/signup.css">
<body>
    <div class="container">
        <h1>Create Account</h1>

        <?php if (!empty($error_message)): ?>
            <p class="error"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <form method="POST">
            <input type="text" name="username" placeholder="Choose a Username" required>
            <input type="password" name="password" placeholder="Choose a Strong Password" required>

            <div class="flex">
                <button type="submit" class="primary-btn">Sign up</button>
                <button type="button" class="secondary-btn" onclick="window.location.href='login.php'">Sign in</button>
            </div>
        </form>
        
        <div>
            <a href="index.php" class="go-back-link">Return to Home</a>
        </div>
    </div>
</body>

</html>