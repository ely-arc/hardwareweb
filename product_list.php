<?php

/*
    NAME: SEVIDAL, JELSY F.
    FILE NAME: product_list.php
    DATE FINISHED: 04-20-2025
    PURPOSE: This code allows an admin to view, update, and delete products
    from the hardware store database in a table format. 
*/

session_start();
require_once 'connect.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_auth.php");
    exit;
}

// Delete product
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: product_list.php");
    exit;
}

// Update product
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $price = $_POST['price'];
    $qty = $_POST['quantity'];

    $stmt = $pdo->prepare("UPDATE products SET name = ?, description = ?, price = ?, quantity = ? WHERE id = ?");
    $stmt->execute([$name, $desc, $price, $qty, $id]);
    header("Location: product_list.php");
    exit;
}

// Fetch products
$stmt = $pdo->prepare("SELECT * FROM products ORDER BY id DESC");
$stmt->execute();
$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Product List - Admin</title>
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

    <table class="product-table">
        <tr>
            <th>Image</th>
            <th>Name</th>
            <th>Description</th>
            <th>Price (₱)</th>
            <th>Quantity</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($products as $product): ?>
        <tr>
            <form method="POST">
                <td><img src="<?php echo htmlspecialchars($product['image_path']); ?>" width="60"></td>
                <td><input type="text" name="name" value="<?php echo htmlspecialchars($product['name']); ?>"></td>
                <td><textarea name="description"><?php echo htmlspecialchars($product['description']); ?></textarea></td>
                <td><input type="number" step="0.01" name="price" value="<?php echo $product['price']; ?>"></td>
                <td><input type="number" name="quantity" value="<?php echo $product['quantity']; ?>"></td>
                <td>
                    <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                    <button class="btn btn-update" name="update">Update</button>
                    <a href="product_list.php?delete=<?php echo $product['id']; ?>" class="btn btn-delete" onclick="return confirm('Delete this product?')">Delete</a>
                </td>
            </form>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>

<style>

h1 {
      background-color: #2c3e50;
      color: white;
      padding: 20px;
      text-align: center;
      margin: 0;
    }
        .product-table {
            width: 90%;
            margin: auto;
            border-collapse: collapse;
        }

        .product-table th, .product-table td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }

        .product-table th {
            background-color: #2c3e50;
            color: white;
        }

        .product-table input[type="text"],
        .product-table input[type="number"],
        .product-table textarea {
            width: 90%;
        }

        .btn {
            padding: 6px 10px;
            border: none;
            cursor: pointer;
        }

        .btn-update {
            background-color: #3498db;
            color: white;
        }

        .btn-delete {
            background-color: #e74c3c;
            color: white;
        }

        .btn:hover {
            opacity: 0.9;
        }
    </style>
