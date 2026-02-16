<?php

/*
    NAME: SEVIDAL, JELSY F.
    FILE NAME: login_admin.php
    DATE FINISHED: 04-20-2025
    PURPOSE: This code creates a simple admin page that register a new account or 
    log in using a single form that switches between "Login" and "Register" modes.
    saving admin info in database securely with password hashing.
*/


session_start();
require_once 'connect.php';

$mode = $_POST['mode'] ?? 'login';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'];

    if ($mode === 'register') {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $message = "Invalid email address.";
            $mode = 'register';
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO ecom_admins (username, email, password) VALUES (?, ?, ?)");
            try {
                $stmt->execute([$username, $email, $hashed]);
                $message = "Registered successfully. Please login.";
                $mode = 'login';
            } catch (PDOException $e) {
                $message = "Username or email already exists.";
                $mode = 'register';
            }
        }
    }

    if ($mode === 'login') {
        $stmt = $pdo->prepare("SELECT * FROM ecom_admins WHERE username = ?");
        $stmt->execute([$username]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            header("Location: admin_feed.php");
            exit;
        } else {
            $message = "Incorrect username or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin login/register</title>
</head>
<body>
<div class="form-wrapper">
    <h2><?php echo ucfirst($mode); ?> Admin</h2>

    <?php if ($message): ?>
        <div class="notice"><?php echo $message; ?></div>
    <?php endif; ?>

    <form method="POST">
        <input type="hidden" name="mode" value="<?php echo $mode; ?>">

        <input type="text" name="username" placeholder="Username" required>

        <?php if ($mode === 'register'): ?>
            <input type="email" name="email" placeholder="Email Address" required>
        <?php endif; ?>

        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="submit"><?php echo ucfirst($mode); ?></button>
    </form>

    <div class="toggle-mode">
        <form method="POST">
            <input type="hidden" name="mode" value="<?php echo $mode === 'login' ? 'register' : 'login'; ?>">
            <button type="submit"><?php echo $mode === 'login' ? 'Switch to Register' : 'Switch to Login'; ?></button>
        </form>
    </div>
</div>
</body>
</html>


<style>
body {
    font-family: sans-serif;
    background-color: #f0f0f0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.form-wrapper {
    background: #fff;
    padding: 25px;
    border-radius: 6px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
    width: 320px;
}

.form-wrapper h2 {
    text-align: center;
    margin-bottom: 15px;
    color: #333;
}

.form-wrapper input {
    width: 100%;
    padding: 10px;
    margin: 6px 0;
    border: 1px solid #ccc;
    border-radius: 4px;
}

.form-wrapper button {
    width: 100%;
    padding: 10px;
    margin-top: 12px;
    background: #333;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.toggle-mode {
    text-align: center;
    margin-top: 15px;
}

.notice {
    color: red;
    text-align: center;
    margin-bottom: 10px;
}
</style>
