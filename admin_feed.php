<?php

/*
    NAME: SEVIDAL, JELSY F.
    FILE NAME: admin_feed.php
    DATE FINISHED: 04-20-2025
    PURPOSE: The purpose of this page is to allow the admin to view all added products in a clean, 
    organized feed layout. It helps the admin quickly monitor product details like name, 
    description, price, and stock quantity.
*/

session_start();
require_once 'connect.php'; 
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_auth.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM products ORDER BY id DESC");
$stmt->execute();
$products = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Feed - Hardware Store</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <h1>Admin Feed</h1>
    
    <nav>
    <a href="admin_feed.php">📊 Feed</a>
    <a href="manage_products.php">📦 Add Products</a>
    <a href="product_list.php">Product List</a>
    <a href="Adminmanage_orders.php">🧾 Manage Orders</a>
    <a href="admin_reports.php">📈 Reports</a>
    <a href="login_admin.php" style="color: red;">🚪 Logout</a>
    </nav>

    <div class="product-feed">
        <?php if (count($products) > 0): ?>
            <?php foreach ($products as $product): ?>
                <div class="product-item">
                    <img src="<?php echo $product['image_path']; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-image">
                    <div class="product-info">
                        <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                        <p><strong>Description:</strong> <?php echo htmlspecialchars($product['description']); ?></p>
                        <p><strong>Price:</strong> ₱<?php echo number_format($product['price'], 2); ?></p>
                        <p><strong>Quantity:</strong> <?php echo $product['quantity']; ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No products found in the feed.</p>
        <?php endif; ?>
    </div>
</body>
</html>





<style>
h1 {
  background-color: #2c3e50;
  color: white;
  text-align: center;
  padding: 20px 0 10px;
  margin: 0;
  font-size: 28px;
  letter-spacing: 1px;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
}

h2 {
  text-align: center;
  background-color: #2c3e50;
  color: #fdfdfd;
  margin: 0;
  padding-bottom: 15px;
  font-size: 20px;
  font-weight: 500;
}




/* Product Feed Container */
.product-feed {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); /* Responsive grid */
    gap: 20px;
    padding: 20px;
}

/* Individual Product Item */
.product-item {
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

/* Hover effect on product items */
.product-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
}

/* Product Image */
.product-image {
    width: 100%;
    height: 200px;
    object-fit: cover;
    border-bottom: 1px solid #ddd;
}

/* Product Info Section */
.product-info {
    padding: 15px;
}

/* Product Name */
.product-info h3 {
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 10px;
    color: #333;
}

/* Product Description */
.product-info p {
    font-size: 14px;
    color: #555;
    line-height: 1.5;
    margin-bottom: 8px;
}

/* Price Styling */
.product-info p strong {
    font-weight: 600;
    color: #333;
}

.product-info p {
    margin-top: 10px;
}

/* Styling for Price */
.product-info .price {
    font-size: 16px;
    font-weight: bold;
    color: #27ae60; /* Green color for price */
}

/* Styling for Quantity */
.product-info .quantity {
    font-size: 14px;
    font-weight: 500;
    color: #e74c3c; /* Red color for quantity */
}
</style>


