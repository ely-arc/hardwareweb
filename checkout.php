<?php
/*
    NAME: SEVIDAL, JELSY F.
    FILE NAME: checkout.php
    DATE FINISHED: 04-11-2025
    PURPOSE: This checkout page collects customer details, displays their cart summary, and processes their order by saving it to the database.
    After a successful order, it clears the cart and redirects the user to a receipt page.
*/

session_start();
require_once 'connect.php'; // DB connection

// ✅ Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// ✅ If cart is empty, exit early
if (empty($_SESSION['cart'])) {
    echo "Your cart is empty.";
    exit;
}

// ✅ Fetch cart product details from database
$cartDetails = [];
$totalPrice = 0;

$productIds = array_keys($_SESSION['cart']);
$placeholders = implode(',', array_fill(0, count($productIds), '?'));

$stmt = $pdo->prepare("SELECT id, name, price FROM products WHERE id IN ($placeholders)");
$stmt->execute($productIds);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($products as $product) {
    $product_id = $product['id'];
    $quantity = $_SESSION['cart'][$product_id];
    $cartDetails[$product_id] = [
        'name' => $product['name'],
        'price' => $product['price'],
        'quantity' => $quantity
    ];
    $totalPrice += $product['price'] * $quantity;
}

// ✅ Handle form submission
if (isset($_POST['order'])) {
    $fullname = $_POST['fullname'] ?? '';
    $address = $_POST['address'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $payment_method = $_POST['payment_method'] ?? '';
    $order_items = '';

    foreach ($cartDetails as $item) {
        $order_items .= $item['name'] . " (x" . $item['quantity'] . "), ";
    }

    $order_items = rtrim($order_items, ", ");

    try {
        $pdo->beginTransaction();

        // Insert order
        $stmt = $pdo->prepare("INSERT INTO customer_order (fullname, address, phone, order_items, payment_method, total_price, order_date) 
        VALUES (:fullname, :address, :phone, :order_items, :payment_method, :total_price, NOW())");

        $stmt->execute([
            ':fullname' => $fullname,
            ':address' => $address,
            ':phone' => $phone,
            ':order_items' => $order_items,
            ':payment_method' => $payment_method,
            ':total_price' => $totalPrice
        ]);

        $order_id = $pdo->lastInsertId();
        $pdo->commit();

        unset($_SESSION['cart']); // Clear cart

        // ✅ Redirect to receipt page
        header("Location: receipt.php?order_id=" . urlencode($order_id));
        exit;

    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout - Customer Details</title>
    <link rel="stylesheet" href="userheader.css">
</head>
<body>

<h1>Check Out</h1>
<nav>
    <a href="feed_user.php">Home</a> |
    <a href="product_user.php">Products</a> |
    <a href="cart_user.php">🛒Cart</a> |
    <a href="usertrack_order.php">Track Orders</a>
    <a href="login.php" style="color: red;">🚪 Logout</a>
</nav>

<h2>Customer Information</h2>

<form action="checkout.php" method="POST">
    <label for="fullname">Full Name:</label><br>
    <input type="text" id="fullname" name="fullname" required><br><br>

    <label for="address">Shipping Address:</label><br>
    <input type="text" id="address" name="address" required><br><br>

    <label for="phone">Phone Number:</label><br>
    <input type="text" id="phone" name="phone" required><br><br>

    <label for="payment_method">Payment Method:</label><br>
    <select id="payment_method" name="payment_method" required>
        <option value="">--Select Payment Method--</option>
        <option value="Cash on Delivery">Cash on Delivery</option>
        <option value="Bank Transfer">Bank Transfer</option>
        <option value="Credit Card">Credit Card</option>
    </select><br><br>

    <h3>Your Cart:</h3>
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cartDetails as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                    <td>₱<?php echo number_format($item['price'], 2); ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td>₱<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h4>Total Price: ₱<?php echo number_format($totalPrice, 2); ?></h4>

    <button type="submit" name="order">Place Order</button>
</form>

</body>
</html>






<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f8f9fa;
        margin: 30px;
    }
    h2, h3, h4 {
        text-align: center;
    }
    form {
        background: #ffffff;
        padding: 20px;
        border-radius: 10px;
        max-width: 500px; /* Slightly smaller form width */
        margin: 0 auto;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    label {
        display: block;
        margin-top: 10px;
        font-weight: bold;
        font-size: 14px;
    }
    input, select {
        width: 90%; /* Smaller width */
        padding: 8px; /* Smaller padding */
        margin-top: 5px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 14px; /* Smaller text */
    }
    table {
        width: 100%;
        margin-top: 20px;
        border-collapse: collapse;
        font-size: 14px; /* Smaller table text */
    }
    th, td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: center;
    }
    th {
        background-color: #007bff;
        color: white;
    }
    tr:nth-child(even) {
        background-color: #f2f2f2;
    }
    button {
        width: 100%;
        margin-top: 20px;
        padding: 10px;
        background-color: #28a745;
        color: white;
        border: none;
        font-size: 16px;
        border-radius: 5px;
        cursor: pointer;
    }
    button:hover {
        background-color: #218838;
    }
</style>

