<?php

/*
    NAME: SEVIDAL, JELSY F.
    FILE NAME: manage_product.php
    DATE FINISHED: 04-20-2025
    PURPOSE: The purpose of this page is to allow the admin to add new products to the online store 
    by filling out a form with product details and an image.
*/

session_start();
require_once 'connect.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_auth.php");
    exit;
}

$message = '';

// --- ADD PRODUCT ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        if (getimagesize($_FILES["image"]["tmp_name"]) !== false) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $stmt = $pdo->prepare("INSERT INTO products (name, description, price, quantity, image_path) VALUES (?, ?, ?, ?, ?)");
                try {
                    $stmt->execute([$name, $description, $price, $quantity, $target_file]);
                    $message = "✅ Product added successfully!";
                } catch (PDOException $e) {
                    $message = "❌ Error: " . $e->getMessage();
                }
            } else {
                $message = "❌ Error uploading the file.";
            }
        } else {
            $message = "❌ File is not a valid image.";
        }
    } else {
        $message = "❌ Please select an image.";
    }
}

// --- FETCH PRODUCTS ---
$stmt = $pdo->prepare("SELECT * FROM products ORDER BY id DESC");
$stmt->execute();
$products = $stmt->fetchAll();
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Manage Products - Admin</title>
</head>
<body>
  <link rel="stylesheet" href="admin.css">

  <h1>Admin Add Product</h1>

  <nav>
    <a href="admin_feed.php">📊 Feed</a>
    <a href="manage_products.php">📦 Add Products</a>
    <a href="product_list.php">Product List</a>
    <a href="Adminmanage_orders.php">🧾 Manage Orders</a>
    <a href="admin_reports.php">📈 Reports</a>
    <a href="login_admin.php" style="color: red;">🚪 Logout</a>
    </nav>

  <div class="container">
    <form action="manage_products.php" method="POST" enctype="multipart/form-data">
      <h2>Add New Product</h2>
      <p><?php echo $message; ?></p>

      <label for="name">Product Name:</label>
      <input type="text" name="name" id="name" required>

      <label for="description">Description:</label>
      <textarea name="description" id="description" required></textarea>

      <label for="price">Price (₱):</label>
      <input type="number" name="price" id="price" step="0.01" required>

      <label for="quantity">Quantity:</label>
      <input type="number" name="quantity" id="quantity" required>

      <label for="image">Product Image:</label>
      <input type="file" name="image" id="image" accept="image/*" required>

      <button type="submit" name="submit">Add Product</button>
    </form>
  </div>

</body>
</html>


<style>
.container {
  background: white;
  padding: 20px;
  border-radius: 12px;
  max-width: 600px;
  margin: 0 auto 40px auto;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.container label {
  display: block;
  margin-top: 10px;
  font-weight: bold;
}

.container input[type="text"],
.container input[type="number"],
.container textarea,
.container input[type="file"] {
  width: 100%;
  padding: 8px;
  margin-top: 5px;
  border-radius: 6px;
  border: 1px solid #ccc;
  box-sizing: border-box;
}

.container button {
  margin-top: 15px;
  background-color: #28a745;
  border: none;
  color: white;
  padding: 10px 16px;
  font-size: 16px;
  border-radius: 8px;
  cursor: pointer;
}

.container button:hover {
  background-color: #218838;

}

</style>
