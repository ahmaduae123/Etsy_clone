<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo '<script>redirect("login.php");</script>';
    exit;
}

$user_id = $_SESSION['user_id'];

// Handle actions: add to cart, update quantity, remove item
if (isset($_GET['action'])) {
    if ($_GET['action'] == 'add' && isset($_GET['product_id'])) {
        $product_id = $conn->real_escape_string($_GET['product_id']);
        $check = $conn->query("SELECT * FROM cart WHERE user_id = '$user_id' AND product_id = '$product_id'");
        if ($check->num_rows > 0) {
            $conn->query("UPDATE cart SET quantity = quantity + 1 WHERE user_id = '$user_id' AND product_id = '$product_id'");
        } else {
            $conn->query("INSERT INTO cart (user_id, product_id, quantity) VALUES ('$user_id', '$product_id', 1)");
        }
        echo '<script>redirect("cart.php");</script>';
    } elseif ($_GET['action'] == 'remove' && isset($_GET['product_id'])) {
        $product_id = $conn->real_escape_string($_GET['product_id']);
        $conn->query("DELETE FROM cart WHERE user_id = '$user_id' AND product_id = '$product_id'");
        echo '<script>redirect("cart.php");</script>';
    } elseif ($_GET['action'] == 'update' && isset($_POST['product_id']) && isset($_POST['quantity'])) {
        $product_id = $conn->real_escape_string($_POST['product_id']);
        $quantity = $conn->real_escape_string($_POST['quantity']);
        if ($quantity > 0) {
            $conn->query("UPDATE cart SET quantity = '$quantity' WHERE user_id = '$user_id' AND product_id = '$product_id'");
        }
        echo '<script>redirect("cart.php");</script>';
    }
}

// Fetch cart items
$cart_items = $conn->query("SELECT c.id, c.product_id, c.quantity, p.name, p.price, p.image 
                            FROM cart c 
                            JOIN products p ON c.product_id = p.id 
                            WHERE c.user_id = '$user_id'");
$total = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart - Etsy Clone</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background: #f9f9f9; color: #333; }
        .header { background: #ff6f61; color: white; padding: 20px; text-align: center; }
        .nav { background: #333; padding: 10px; }
        .nav a { color: white; text-decoration: none; margin: 0 15px; font-size: 18px; }
        .nav a:hover { color: #ff6f61; }
        .cart-container { max-width: 1000px; margin: 20px auto; padding: 20px; background: white; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .cart-item { display: flex; align-items: center; padding: 15px; border-bottom: 1px solid #ddd; }
        .cart-item img { max-width: 100px; height: auto; border-radius: 5px; margin-right: 20px; }
        .cart-item-details { flex: 1; }
        .cart-item-details h3 { color: #ff6f61; margin: 0 0 10px; }
        .cart-item-details p { margin: 5px 0; font-size: 16px; }
        .quantity-form { display: inline-block; }
        .quantity-form input { width: 60px; padding: 5px; border: 1px solid #ddd; border-radius: 5px; }
        .btn { background: #ff6f61; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; margin: 5px; }
        .btn:hover { background: #e65b50; }
        .total { text-align: right; padding: 20px; font-size: 20px; font-weight: bold; color: #ff6f61; }
        .empty { text-align: center; padding: 20px; font-size: 18px; color: #555; }
        @media (max-width: 600px) {
            .cart-item { flex-direction: column; align-items: flex-start; }
            .cart-item img { max-width: 100%; margin: 0 0 10px; }
            .cart-container { margin: 10px; padding: 15px; }
            .btn { width: 100%; box-sizing: border-box; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Your Cart</h1>
    </div>
    <div class="nav">
        <a href="#" onclick="redirect('index.php')">Home</a>
        <a href="#" onclick="redirect('products.php')">Products</a>
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'seller') { ?>
            <a href="#" onclick="redirect('add_product.php')">Add Product</a>
        <?php } ?>
        <?php if (isset($_SESSION['user'])) { ?>
            <a href="#" onclick="redirect('logout.php')">Logout</a>
        <?php } ?>
    </div>
    <div class="cart-container">
        <?php if ($cart_items->num_rows > 0) { ?>
            <?php while ($item = $cart_items->fetch_assoc()) { 
                $subtotal = $item['price'] * $item['quantity'];
                $total += $subtotal;
            ?>
                <div class="cart-item">
                    <img src="<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>">
                    <div class="cart-item-details">
                        <h3><?php echo $item['name']; ?></h3>
                        <p>Price: $<?php echo number_format($item['price'], 2); ?></p>
                        <p>Subtotal: $<?php echo number_format($subtotal, 2); ?></p>
                        <form class="quantity-form" method="POST" action="cart.php?action=update">
                            <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                            <label for="quantity">Quantity:</label>
                            <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1">
                            <button type="submit" class="btn">Update</button>
                        </form>
                        <button class="btn" onclick="redirect('cart.php?action=remove&product_id=<?php echo $item['product_id']; ?>')">Remove</button>
                    </div>
                </div>
            <?php } ?>
            <div class="total">
                Total: $<?php echo number_format($total, 2); ?>
            </div>
            <div style="text-align: right; padding: 20px;">
                <button class="btn" onclick="redirect('checkout.php')">Proceed to Checkout</button>
            </div>
        <?php } else { ?>
            <div class="empty">Your cart is empty. <a href="#" onclick="redirect('products.php')">Shop now!</a></div>
        <?php } ?>
    </div>
    <script>
        function redirect(url) {
            window.location.href = url;
        }
    </script>
</body>
</html>
<?php $conn->close(); ?>
