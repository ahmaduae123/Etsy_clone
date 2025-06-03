<?php
session_start();
include 'db.php';
$featured = $conn->query("SELECT * FROM products WHERE is_featured = 1 LIMIT 4");
$trending = $conn->query("SELECT * FROM products ORDER BY rating DESC LIMIT 4");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Etsy Clone - Home</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background: #f9f9f9; color: #333; }
        .header { background: #ff6f61; color: white; padding: 20px; text-align: center; }
        .nav { background: #333; padding: 10px; }
        .nav a { color: white; text-decoration: none; margin: 0 15px; font-size: 18px; }
        .nav a:hover { color: #ff6f61; }
        .section { padding: 20px; }
        .product-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; }
        .product { background: white; border-radius: 10px; padding: 15px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); text-align: center; }
        .product img { max-width: 100%; height: auto; border-radius: 5px; }
        .product h3 { color: #ff6f61; margin: 10px 0; }
        .product p { font-size: 16px; color: #555; }
        .btn { background: #ff6f61; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; }
        .btn:hover { background: #e65b50; }
        @media (max-width: 600px) { .product-grid { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
    <div class="header">
        <h1>Etsy Clone</h1>
        <p>Welcome, <?php echo isset($_SESSION['user']) ? $_SESSION['user'] : 'Guest'; ?>!</p>
    </div>
    <div class="nav">
        <a href="index.php">Home</a>
        <a href="#" onclick="redirect('signup.php')">Signup</a>
        <a href="#" onclick="redirect('login.php')">Login</a>
        <a href="#" onclick="redirect('products.php')">Products</a>
        <a href="#" onclick="redirect('cart.php')">Cart</a>
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'seller') { ?>
            <a href="#" onclick="redirect('add_product.php')">Add Product</a>
        <?php } ?>
        <?php if (isset($_SESSION['user'])) { ?>
            <a href="#" onclick="redirect('logout.php')">Logout</a>
        <?php } ?>
    </div>
    <div class="section">
        <h2>Featured Products</h2>
        <div class="product-grid">
            <?php while ($row = $featured->fetch_assoc()) { ?>
                <div class="product">
                    <img src="<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
                    <h3><?php echo $row['name']; ?></h3>
                    <p>$<?php echo $row['price']; ?></p>
                    <button class="btn" onclick="redirect('cart.php?action=add&product_id=<?php echo $row['id']; ?>')">Add to Cart</button>
                </div>
            <?php } ?>
        </div>
    </div>
    <div class="section">
        <h2>Trending Products</h2>
        <div class="product-grid">
            <?php while ($row = $trending->fetch_assoc()) { ?>
                <div class="product">
                    <img src="<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
                    <h3><?php echo $row['name']; ?></h3>
                    <p>$<?php echo $row['price']; ?></p>
                    <button class="btn" onclick="redirect('cart.php?action=add&product_id=<?php echo $row['id']; ?>')">Add to Cart</button>
                </div>
            <?php } ?>
        </div>
    </div>
    <script>
        function redirect(url) {
            window.location.href = url;
        }
    </script>
</body>
</html>
<?php $conn->close(); ?>
