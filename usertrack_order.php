<?php 

/*
    NAME: SEVIDAL, JELSY F.
    FILE NAME: usertrack_order.php
    DATE FINISHED: 04-11-2025
    PURPOSE: This code displays a My Orders page where user can view their past 
    orders, showing details like order ID, items, total amount, date, and status. 
    It fetches order records from the database based on the user's ID and displays them in a 
    styled table using css. 
*/

session_start();
require_once 'connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM customer_order WHERE fullname = (SELECT fullname FROM users WHERE id = ?) ORDER BY order_date DESC");
$stmt->execute([$_SESSION['user_id']]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Track My Orders</title>
    <link rel="stylesheet" href="userheader.css">
</head>
<body>

<h1>My Orders</h1>
<nav>
    <a href="feed_user.php">Home</a> |
    <a href="product_user.php">Products</a> |
    <a href="cart_user.php">🛒Cart</a> |
    <a href="usertrack_order.php">Track Orders</a> |
    <a href="login.php" style="color: red;">🚪 Logout</a>
</nav>

<table border="1" cellpadding="10">
    <thead>
        <tr>
            <th>Order ID</th>
            <th>Order Items</th>
            <th>Total</th>
            <th>Placed On</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($orders as $order): ?>
            <tr>
                <td><?php echo $order['id']; ?></td>
                <td><?php echo htmlspecialchars($order['order_items']); ?></td>
                <td>₱<?php echo number_format($order['total_price'], 2); ?></td>
                <td><?php echo $order['order_date']; ?></td>
                <td><?php echo htmlspecialchars($order['order_status'] ?? 'Pending'); ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>

<style>
    table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    font-size: 14px;
    background-color: #fff;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

th, td {
    padding: 12px 16px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

thead th {
    background-color: #2d6a4f;
    color: #fff;
    font-weight: 600;
    letter-spacing: 0.5px;
}

tbody tr:nth-child(even) {
    background-color: #f9f9f9;
}

tbody tr:hover {
    background-color: #f1f1f1;
    transition: background-color 0.2s ease;
}

td {
    color: #333;
}

th:first-child,
td:first-child {
    border-left: 4px solid #2d6a4f;
}

</style>
