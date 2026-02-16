<?php

/*
    NAME: SEVIDAL, JELSY F.
    FILE NAME: adminmanage_orders.php
    DATE FINISHED:  04-20-2025
    PURPOSE: Creates an admin page for managing customer orders, 
    allowing the admin to view all orders and update each order’s status 
    (Pending, Shipped, Delivered, or Cancelled). It retrieves orders from the database.
*/

session_start();
require_once 'connect.php';

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['order_status'])) {
    $stmt = $pdo->prepare("UPDATE customer_order SET order_status = ? WHERE id = ?");
    $stmt->execute([$_POST['order_status'], $_POST['order_id']]);
    $_SESSION['message'] = "Order #" . $_POST['order_id'] . " status updated to " . $_POST['order_status'];
    header("Location: Adminmanage_orders.php"); // Prevent form resubmission
    exit;
}

// Fetch all orders
$stmt = $pdo->query("SELECT * FROM customer_order ORDER BY order_date DESC");
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Orders - Admin</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>

<h1>Manage Orders</h1>
<nav>
    <a href="admin_feed.php">Feed</a>
    <a href="manage_products.php">📦 Add Products</a>
    <a href="product_list.php">Product List</a>
    <a href="Adminmanage_orders.php">🧾 Manage Orders</a>
    <a href="admin_reports.php">📈 Reports</a>
    <a href="login_admin.php" style="color: red;">🚪 Logout</a>
</nav>

<?php if (!empty($_SESSION['message'])): ?>
    <p class="message"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></p>
<?php endif; ?>

<table border="1" cellpadding="10" cellspacing="0">
    <thead>
        <tr>
            <th>Order ID</th>
            <th>Customer Name</th>
            <th>Order Items</th>
            <th>Total Price</th>
            <th>Order Date</th>
            <th>Order Status</th>
            <th>Update Status</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($orders as $order): ?>
            <tr>
                <td><?php echo htmlspecialchars($order['id']); ?></td>
                <td><?php echo htmlspecialchars($order['fullname']); ?></td>
                <td><?php echo htmlspecialchars($order['order_items']); ?></td>
                <td>₱<?php echo number_format($order['total_price'], 2); ?></td>
                <td><?php echo htmlspecialchars($order['order_date']); ?></td>
                <td><?php echo htmlspecialchars($order['order_status'] ?? 'Pending'); ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                        <select name="order_status">
                            <option value="Pending" <?php echo ($order['order_status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                            <option value="Shipped" <?php echo ($order['order_status'] == 'Shipped') ? 'selected' : ''; ?>>Shipped</option>
                            <option value="Delivered" <?php echo ($order['order_status'] == 'Delivered') ? 'selected' : ''; ?>>Delivered</option>
                            <option value="Cancelled" <?php echo ($order['order_status'] == 'Cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                        </select>
                        <button type="submit">Update</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>


<style>

.message {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
    padding: 12px;
    margin: 10px 0 20px 0;
    border-radius: 5px;
    width: fit-content;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 30px;
    background-color: #fff;
    box-shadow: 0 2px 5px rgba(0,0,0,0.05);
}

th, td {
    text-align: left;
    padding: 12px 15px;
    border-bottom: 1px solid #e0e0e0;
}

th {
    background-color: #007bff;
    color: white;
    font-weight: normal;
}

tr:nth-child(even) {
    background-color: #f2f2f2;
}

select {
    padding: 6px 10px;
    border-radius: 4px;
    border: 1px solid #ccc;
    background-color: #fff;
}

button[name="update_status"] {
    background-color: #28a745;
    border: none;
    color: white;
    padding: 6px 12px;
    margin-left: 8px;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

button[name="update_status"]:hover {
    background-color: #218838;
}

</style>