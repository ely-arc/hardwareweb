<?php
/*
    NAME: SEVIDAL, JELSY F.
    FILE NAME: feed_user
    DATE FINISHED: 04-09-2025
    PURPOSE: Main interface for logged-in users in the hardware store system.
    It ensures that only authenticated users can access the dashboard,
    displays a personalized welcome message, and provides options to logout or browse items.
    include its own css design below.
*/

session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$fname = $_SESSION['fname'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Hardware Dashboard</title>
</head>

<body>
  <div class="background">
    <header>
      <h1>Welcome, <?php echo htmlspecialchars($fname); ?>!</h1>
      <p>Power Up Your Projects with Quality Tools!</p>
<nav>
    <a href="feed_user.php">Home</a> |
    <a href="product_user.php">Products</a> |
    <a href="cart_user.php">🛒Cart</a> |
    <a href="usertrack_order.php">Track Orders</a>
    <a href="login.php" style="color: red;">🚪 Logout</a>
</nav>
    </header>

    <div class="center-content">
      <button class="view-items-btn" onclick="window.location.href='product_user.php';">Shop Tools Now</button>
    </div>
 
    <footer>
      "Building Success, One Tool at a Time!" | Hardware Supply Co.
    </footer>
  </div>
</body>
</html>

<style>

h1{
  background-color: #229954; 
  color: white;
  text-align: center;
  padding: 20px 0 10px;
  margin: 0;
  font-size: 28px;
  letter-spacing: 1px;
}
p {
  background-color: #229954;
  color: white;
  text-align: center;
  padding: 10px 20px;
  margin: 0;
  font-size: 18px;
  letter-spacing: 0.5px;
}
nav{
  background-color: #27ae60; /* Rich green */
  padding: 12px 0;
  text-align: center;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

nav a {
  color: #eafaf1; /* Soft off-white with green tint */
  text-decoration: none;
  margin: 0 15px;
  font-size: 16px;
  font-weight: 500;
  transition: color 0.3s ease, text-shadow 0.3s ease;
}

nav a:hover {
  color: #ffffff;
  text-shadow: 0 0 6px rgba(46, 204, 113, 0.6); 
}


.background {
  background: url('feedbg.jpg') no-repeat center center;
  background-size: cover;
  width: 100%;
  height: 100vh; 
  display: flex;
  flex-direction: column;
}

footer {
  background-color: rgba(34, 70, 34, 0.8); 
  color: white;
  padding: 10px 20px;
  text-align: center;
  z-index: 2;
  flex-shrink: 0;
  font-size: 12px;
  margin-top: auto;
}

.center-content {
  flex: 1;
  display: flex;
  justify-content: center;
  align-items: center;
  position: relative;
  z-index: 1;
}

.view-items-btn {
  background: #66bb6a;
  color: white;
  padding: 15px 30px;
  font-size: 20px;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  transition: background 0.3s;
  box-shadow: 0 4px 8px rgba(0,0,0,0.3);
}

.view-items-btn:hover {
  background: #43a047;
}
</style>
