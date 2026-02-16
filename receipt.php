<?php

/*
    NAME: SEVIDAL, JELSY F.
    FILE NAME: receipt.php
    DATE FINISHED: 04-11-2025
    PURPOSE: This code displays a receipt for a customer's hardware store order by 
    fetching order details from a database and showing them in a styled HTML page. 
    It also provides buttons to download the receipt as PDF or view all orders. 
*/

session_start();
require_once 'connect.php';
require('fpdf/fpdf.php'); // Include FPDF library

$order_id = $_GET['order_id'] ?? null;

if (!$order_id) {
    echo "No order ID found.";
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM customer_order WHERE id = ?");
$stmt->execute([$order_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    echo "Order not found for ID: " . htmlspecialchars($order_id);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Receipt - Order #<?php echo $order_id; ?></title>
</head>
<body>
<div class="receipt-container">
    <div class="receipt-header">
        <h1>Hardware Store Receipt</h1>
    </div>

    <div class="order-details">
        <h2>Order Details - #<?php echo $order_id; ?></h2>
        <table>
            <tr><th>Customer Name</th><td><?php echo htmlspecialchars($order['fullname']); ?></td></tr>
            <tr><th>Address</th><td><?php echo htmlspecialchars($order['address']); ?></td></tr>
            <tr><th>Phone</th><td><?php echo htmlspecialchars($order['phone']); ?></td></tr>
            <tr><th>Payment Method</th><td><?php echo htmlspecialchars($order['payment_method']); ?></td></tr>
            <tr><th>Order Date</th><td><?php echo htmlspecialchars($order['order_date']); ?></td></tr>
        </table>
    </div>

    <div class="order-details">
        <h3>Items Ordered</h3>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $totalPrice = 0;
                $order_items = explode(", ", $order['order_items']);
                foreach ($order_items as $item) {
                    $item_details = explode(" (x", $item);
                    $item_name = $item_details[0];
                    $item_quantity = rtrim($item_details[1], ")");
                    $item_price = 100; // Example price
                    $item_total = $item_price * $item_quantity;
                    $totalPrice += $item_total;

                    echo "<tr>";
                    echo "<td>{$item_name}</td>";
                    echo "<td>₱{$item_price}</td>";
                    echo "<td>{$item_quantity}</td>";
                    echo "<td>₱{$item_total}</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <div class="total-section">
        <h4>Total Price: ₱<?php echo $totalPrice; ?></h4>
    </div>


    <div class="button-container">
        <form method="POST" action="download_pdf.php">
            <input type="hidden" name="order_id" value="<?php echo $order_id; ?>" />
            <button type="submit">Download PDF Receipt</button>
        </form>

        <form action="usertrack_order.php" method="get">
            <button type="submit">View Orders</button>
        </form>
    </div>
</div>
</body>
</html>


<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Courier New', Courier, monospace;
    background-color: #f4f4f4;
    color: #333;
    display: flex;
    justify-content: center;
    align-items: flex-start;
    padding: 40px 10px;
    min-height: 100vh;
}

.receipt-container {
    width: 360px;
    background-color: #fff;
    border: 1px dashed #999;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 0 8px rgba(0, 0, 0, 0.05);
}

.receipt-header {
    text-align: center;
    margin-bottom: 15px;
    padding-bottom: 5px;
    border-bottom: 1px dashed #ccc;
}

.receipt-header h1 {
    font-size: 1.5em;
    font-weight: bold;
    color: #000;
}

.order-details {
    margin-bottom: 20px;
}

.order-details h2,
.order-details h3 {
    font-size: 1em;
    margin-bottom: 10px;
    border-bottom: 1px dashed #ccc;
    padding-bottom: 4px;
    color: #000;
}

.order-details table {
    width: 100%;
    font-size: 0.9em;
    border-collapse: collapse;
}

.order-details th,
.order-details td {
    text-align: left;
    padding: 4px 0;
}

.order-details th {
    color: #444;
    font-weight: bold;
}

.order-details table thead {
    border-bottom: 1px solid #ccc;
}

.order-details table tbody tr:not(:last-child) {
    border-bottom: 1px dotted #ccc;
}

.total-section {
    text-align: right;
    font-size: 1em;
    font-weight: bold;
    margin-top: 10px;
    padding-top: 10px;
    border-top: 1px dashed #ccc;
    color: #000;
}

form {
    margin-top: 20px;
    text-align: center;
}

button {
    background-color: #333;
    color: #fff;
    padding: 6px 15px;
    font-size: 0.9em;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-family: inherit;
    transition: background-color 0.3s ease;
}

button:hover {
    background-color: #000;
}
</style>
