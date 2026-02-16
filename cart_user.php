<?php

/*
    NAME: SEVIDAL, JELSY F.
    FILE NAME: cart_user.php
    DATE FINISHED: 04-11-2025
    PURPOSE: Creates the shopping cart page for the Online Hardware Store, allowing users 
    to view their cart items, update item quantities, remove products, and proceed to checkout. 
    It handles session-based cart management, dynamically fetches product details from the 
    database, and calculates the total price. css below.
*/

session_start();
require_once 'connect.php'; // Your DB connection

// Initialize cart if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle quantity update functionality
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_cart'])) {
    if (isset($_POST['quantity']) && is_array($_POST['quantity'])) {
        foreach ($_POST['quantity'] as $product_id => $quantity) {
            $product_id = (int)$product_id;
            $quantity = max(1, min(10, (int)$quantity)); // Clamp quantity between 1 and 10
            if (isset($_SESSION['cart'][$product_id])) {
                $_SESSION['cart'][$product_id] = $quantity;
            }
        }
        $_SESSION['message'] = "✅ Cart updated!";
    }
    header('Location: cart_user.php');
    exit;
}

// Remove item from cart functionality
if (isset($_GET['remove'])) {
    $removeIndex = (int)$_GET['remove'];
    if (isset($_SESSION['cart'][$removeIndex])) {
        unset($_SESSION['cart'][$removeIndex]); // Remove item from the cart
        $_SESSION['message'] = "❌ Product removed from cart!";
        header('Location: cart_user.php');
        exit;
    }
}

// Fetch product details from the database
$cartDetails = [];
if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
    $productIds = array_keys($_SESSION['cart']);
    $placeholders = implode(',', array_fill(0, count($productIds), '?'));

    $stmt = $pdo->prepare("SELECT id, name, price FROM products WHERE id IN ($placeholders)");
    $stmt->execute($productIds);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Map product data to cart items
    foreach ($products as $product) {
        $cartDetails[$product['id']] = $product;
    }
}

// Calculate total price
$totalPrice = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        if (isset($cartDetails[$product_id])) {
            $itemTotal = $cartDetails[$product_id]['price'] * $quantity;
            $totalPrice += $itemTotal;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Cart</title>
    <link rel="stylesheet" href="userheader.css">
</head>
<body>

<h1>Your Cart</h1>
<nav>
    <a href="feed_user.php">Home</a> |
    <a href="product_user.php">Products</a> |
    <a href="cart_user.php">🛒Cart</a> |
    <a href="usertrack_order.php">Track Orders</a>
    <a href="login.php" style="color: red;">🚪 Logout</a>
</nav>

<?php if (!empty($_SESSION['message'])): ?>
    <p class="message"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></p>
<?php endif; ?>

<section id="cart">
    <?php if (count($_SESSION['cart']) > 0): ?>
        <form method="POST" action="cart_user.php">
            <table border="1" cellpadding="10" cellspacing="0">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($_SESSION['cart'] as $product_id => $quantity): ?>
                        <?php if (isset($cartDetails[$product_id])): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($cartDetails[$product_id]['name']); ?></td>
                                <td>₱<?php echo number_format($cartDetails[$product_id]['price'], 2); ?></td>
                                <td>
                                    <input type="number" name="quantity[<?php echo $product_id; ?>]" value="<?php echo $quantity; ?>" min="1" max="10" style="width: 60px;">
                                </td>
                                <td>₱<?php echo number_format($cartDetails[$product_id]['price'] * $quantity, 2); ?></td>
                                <td>
                                    <a href="cart_user.php?remove=<?php echo $product_id; ?>" class="remove-btn">❌ Remove</a>
                                </td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <h3>Total Price: ₱<?php echo number_format($totalPrice, 2); ?></h3>
            <button type="submit" name="update_cart">Update Cart</button>
        </form>

        <a href="checkout.php" class="checkout-btn">Proceed to Checkout</a>
    <?php else: ?>
        <p>Your cart is empty.</p>
    <?php endif; ?>
</section>

</body>
</html>
