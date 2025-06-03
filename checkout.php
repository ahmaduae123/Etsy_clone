<?php
session_start();
include 'db.php';
 
if (!isset($_SESSION['user_id'])) {
    echo '<script>redirect("login.php");</script>';
    exit;
}
 
$user_id = $_SESSION['user_id'];
 
// Fetch cart items
$cart_items = $conn->query("SELECT c.id, c.product_id, c.quantity, p.name, p.price, p.image 
                            FROM cart c 
                            JOIN products p ON c.product_id = p.id 
                            WHERE c.user_id = '$user_id'");
$total = 0;
 
// Handle dummy payment (simulate order completion)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['complete_order'])) {
    // Simulate payment success (no real payment processing)
    $order_date = date('Y-m-d H:i:s');
    // Clear cart after "payment"
    $conn->query("DELETE FROM cart WHERE user_id = '$user_id'");
    echo '<script>alert("Order placed successfully! Thank you for your purchase."); redirect("index.php");</script>';
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Etsy Clone</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background: #f9f9f9; color: #333; }
        .header { background: #ff6f61; color: white; padding: 20px; text-align: center; }
        .nav { background: #333; padding: 10px; }
        .nav a { color: white; text-decoration: none; margin: 0 15px; font-size: 18px; }
        .nav a:hover { color: #ff6f61; }
        .checkout-container { max-width: 1000px; margin: 20px auto; padding: 20px; background: white; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .cart-item { display: flex; align-items: center; padding: 15px; border-bottom: 1px solid #ddd; }
        .cart-item img { max-width: 100px; height: auto; border-radius: 5px; margin-right: 20px; }
        .cart-item-details { flex: 1; }
        .cart-item-details h3 { color: #ff6f61; margin: 0 0 10px; }
        .cart-item-details p { margin: 5px 0; font-size: 16px; }
        .total { text-align: right; padding: 20px; font-size: 20px; font-weight: bold; color: #ff6f61; }
        .empty { text-align: center; padding: 20px; font-size: 18px; color: #555; }
        .payment-form { max-width: 500px; margin: 20px auto; padding: 20px; background: #f9f9f9; border-radius: 10px; }
        .form-group { margin: 15px 0; }
        .form-group label { display: block; font-size: 18px; margin-bottom: 5px; }
        .form-group input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 16px; }
        .btn { background: #ff6f61; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; width: 100%; }
        .btn:hover { background: #e65b50; }
        @media (max-width: 600px) {
            .cart-item { flex-direction: column; align-items: flex-start; }
            .cart-item img { max-width: 100%; margin: 0 0 10px; }
            .checkout-container, .payment-form { margin: 10px; padding: 15px; }
            .btn { width: 100%; box-sizing: border-box; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Checkout</h1>
    </div>
    <div class="nav">
        <a href="#" onclick="redirect('index.php')">Home</a>
        <a href="#" onclick="redirect('products.php')">Products</a>
        <a href="#" onclick="redirect('cart.php')">Cart</a>
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'seller') { ?>
            <a href="#" onclick="redirect('add_product.php')">Add Product</a>
        <?php } ?>
        <?php if (isset($_SESSION['user'])) { ?>
            <a href="#" onclick="redirect('logout.php')">Logout</a>
        <?php } ?>
    </div>
    <div class="checkout-container">
        <?php if ($cart_items->num_rows > 0) { ?>
            <h2>Your Order</h2>
            <?php while ($item = $cart_items->fetch_assoc()) { 
                $subtotal = $item['price'] * $item['quantity'];
                $total += $subtotal;
            ?>
                <div class="cart-item">
                    <img src="<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>">
                    <div class="cart-item-details">
                        <h3><?php echo $item['name']; ?></h3>
                        <p>Price: $<?php echo number_format($item['price'], 2); ?></p>
                        <p>Quantity: <?php echo $item['quantity']; ?></p>
                        <p>Subtotal: $<?php echo number_format($subtotal, 2); ?></p>
                    </div>
                </div>
            <?php } ?>
            <div class="total">
                Total: $<?php echo number_format($total, 2); ?>
            </div>
            <div class="payment-form">
                <h2>Dummy Payment</h2>
                <p style="color: #555; font-size: 16px;">This is a simulated payment for testing. No real transaction will occur.</p>
                <form method="POST">
                    <div class="form-group">
                        <label for="card_number">Card Number (Dummy)</label>
                        <input type="text" id="card_number" name="card_number" placeholder="1234-5678-9012-3456" required>
                    </div>
                    <div class="form-group">
                        <label for="expiry">Expiry Date (Dummy)</label>
                        <input type="text" id="expiry" name="expiry" placeholder="MM/YY" required>
                    </div>
                    <div class="form-group">
                        <label for="cvv">CVV (Dummy)</label>
                        <input type="text" id="cvv" name="cvv" placeholder="123" required>
                    </div>
                    <input type="hidden" name="complete_order" value="1">
                    <button type="submit" class="btn">Complete Order</button>
                    </form>
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
