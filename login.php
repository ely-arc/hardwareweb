<?php

/*
    NAME: SEVIDAL, JELSY F.
    FILE NAME: login.php
    DATE FINISHED: 04-8-2025
    PURPOSE:  HTML code to create a login page for an Online Hardware Store website (customer side).
*/

session_start();
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (!empty($email) && !empty($password)) {
        // Prepare and execute query
        $stmt = $conn->prepare("SELECT id, fname, password FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['fname'] = $user['fname'];
            header("Location: feed_user.php");
            exit();
        } else {
            echo "Invalid email or password.";
        }
    } else {
        echo "Please fill in all fields.";
    }
}
?>
/*
    NAME: SEVIDAL, JELSY F.
    FILE NAME: login.php
    DATE FINISHED:  
    PURPOSE:
*/

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Online Hardware Store</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="background"></div> <!-- Background element -->
    <div class="container">
        <h2>Login 🛠️</h2>
        <form method="post">
            <input type="email" name="email" placeholder="Email" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
           
            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>
</body>
</html>

