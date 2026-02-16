<?php

/*
    NAME: SEVIDAL, JELSY F.
    FILE NAME: register.php
    DATE FINISHED: 04-8-2025
    PURPOSE: it handle user registration form. It collects information such as the user's first name.... 
    The code checks if the provided email already exists in the database to prevent duplicate
*/

require_once 'db.php';

$message = ""; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fname = trim($_POST['fname']);
    $lname = trim($_POST['lname']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (!empty($fname) && !empty($lname) && !empty($email) && !empty($password)) {
        try {
            // Check if email already exists
            $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);

            if ($stmt->rowCount() > 0) {
                $message = "❌ Email already exists!";
            } else {
                // Secure password hashing
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

                // Insert new user
                $stmt = $conn->prepare("INSERT INTO users (fname, lname, email, password) VALUES (?, ?, ?, ?)");
                if ($stmt->execute([$fname, $lname, $email, $hashedPassword])) {
                    $message = "✅ Registration successful! <a href='login.php'>Login</a>";
                } else {
                    $message = "❌ Error: Registration failed.";
                }
            }
        } catch (PDOException $e) {
            $message = "❌ Database error: " . $e->getMessage();
        }
    } else {
        $message = "❌ All fields are required!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="container">
        <h2>Register</h2>
        <?php if (!empty($message)) { echo "<p style='color:red;'>$message</p>"; } ?>
        <form method="post">
            <input type="text" name="fname" placeholder="First Name" required><br>
            <input type="text" name="lname" placeholder="Last Name" required><br>
            <input type="email" name="email" placeholder="Email" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button type="submit">Register</button>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</body>
</html>
