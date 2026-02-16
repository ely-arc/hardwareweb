<?php

/*
    NAME: SEVIDAL, JELSY F.
    FILE NAME: admin_reports.php
    DATE FINISHED: 04-20-2025
    PURPOSE: This code login admins view customer order history 
    in a table format and provides a button to download the orders as a PDF report.
*/

session_start();
require_once 'connect.php';
require('fpdf/fpdf.php'); 

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Fetch orders from the database
try {
    // Change DESC to ASC to display orders from the oldest to the newest
    $stmt = $pdo->query("SELECT * FROM customer_order ORDER BY order_date ASC");
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    echo "Error retrieving orders: " . $e->getMessage();
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order History</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>

<h1>Product List</h1>
    <nav>
    <a href="admin_feed.php">📊 Feed</a>
    <a href="manage_products.php">📦 Add Products</a>
    <a href="product_list.php">Product List</a>
    <a href="Adminmanage_orders.php">🧾 Manage Orders</a>
    <a href="admin_reports.php">📈 Reports</a>
    <a href="login_admin.php" style="color: red;">🚪 Logout</a>
    </nav>

    <form method="POST" action="reportdownload_pdf.php">
    <button type="submit">Download PDF Report</button>
    </form>



<?php if (count($orders) > 0): ?>
    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Full Name</th>
                <th>Address</th>
                <th>Phone</th>
                <th>Items</th>
                <th>Payment Method</th>
                <th>Total Price</th>
                <th>Order Date</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?php echo htmlspecialchars($order['id']); ?></td>
                    <td><?php echo htmlspecialchars($order['fullname']); ?></td>
                    <td><?php echo htmlspecialchars($order['address']); ?></td>
                    <td><?php echo htmlspecialchars($order['phone']); ?></td>
                    <td><?php echo htmlspecialchars($order['order_items']); ?></td>
                    <td><?php echo htmlspecialchars($order['payment_method']); ?></td>
                    <td>₱<?php echo number_format($order['total_price'], 2); ?></td>
                    <td><?php echo htmlspecialchars($order['order_date']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No orders found.</p>
<?php endif; ?>

</body>
</html>


<style>
table {
    width: 100%;
    margin-top: 20px;
    border-collapse: collapse;
    font-size: 14px;
}
th, td {
    border: 1px solid #ddd;
    padding: 10px;
    text-align: center;
}
th {
    background-color: #007bff;
    color: white;
}
tr:nth-child(even) {
    background-color: #f2f2f2;
}
p {
    text-align: center;
    font-size: 18px;
    margin-top: 40px;
}
</style>
