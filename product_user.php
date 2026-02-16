<?php
/*
    NAME: SEVIDAL, JELSY F.
    FILE NAME:  product_user.php
    DATE FINISHED:  04-09-2025
    PURPOSE:  Displays the customer product feed page for an Online Hardware Store, 
    allowing users to view products and add them to their cart. It also manages cart sessions, 
    fetches product data from the database, and shows success messages when items are added. 
    It has own css design below.
*/


session_start();
require_once 'connect.php'; 
// Initialize cart if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Add to cart functionality
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // If the product is already in the cart, update the quantity, otherwise add it
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }

    // Set message for success
    $_SESSION['message'] = "✅ Product added to cart!";
    // Redirect to avoid resubmission on page refresh
    header('Location: product_user.php');
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
    <title>Customer Feed</title>
    <link rel="stylesheet" href="userheader.css">
</head>
<body>

<h1>Welcome to Our Hardware Store</h1>
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

<div class="product-grid">
    <?php foreach ($products as $product): ?>
        <div class="product-card">
            <img src="<?php echo htmlspecialchars($product['image_path']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
            <h3><?php echo htmlspecialchars($product['name']); ?></h3>
            <p><?php echo htmlspecialchars($product['description']); ?></p>
            <p><strong>₱<?php echo number_format($product['price'], 2); ?></strong></p>

            <form method="POST" action="product_user.php">
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                <input type="number" name="quantity" value="1" min="1" max="<?php echo $product['quantity']; ?>" required>
                <button type="submit" name="add_to_cart">🛒 Add to Cart</button>
            </form>
        </div>
    <?php endforeach; ?>
</div>

</body>
</html>



<style>
.product-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
  gap: 20px;
  padding: 20px;
  max-width: 1200px;
  margin: auto;
}

.product-card {
  background: #fff;
  border-radius: 12px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  overflow: hidden;
  display: flex;
  flex-direction: column;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.product-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
}

.product-card img {
  width: 100%;
  height: 200px;
  object-fit: cover;
}

.product-card h3 {
  margin: 10px;
  font-size: 1.2rem;
  color: #333;
}

.product-card p {
  margin: 0 10px 10px;
  font-size: 0.95rem;
  color: #555;
}

.product-card form {
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 10px;
  gap: 10px;
}

.product-card input[type="number"] {
  width: 60px;
  padding: 6px;
  border-radius: 6px;
  border: 1px solid #ccc;
}

.product-card button {
  background-color: #28a745;
  color: white;
  border: none;
  padding: 6px 12px;
  border-radius: 6px;
  cursor: pointer;
  font-size: 0.9rem;
}

.product-card button:hover {
  background-color: #218838;
}

@media (max-width: 768px) {
  .product-grid {
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    gap: 15px;
    padding: 15px;
  }

  .product-card img {
    height: 160px;
  }

  .product-card h3 {
    font-size: 1rem;
  }

  .product-card p {
    font-size: 0.85rem;
  }

  .product-card button {
    font-size: 0.8rem;
  }
}

</style>
