<?php
session_start();
include 'db.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'seller') {
    echo '<script>redirect("index.php");</script>';
    exit;
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $description = $conn->real_escape_string($_POST['description']);
    $price = $conn->real_escape_string($_POST['price']);
    $category = $conn->real_escape_string($_POST['category']);
    $stock = $conn->real_escape_string($_POST['stock']);
    $image = $conn->real_escape_string($_POST['image']);
    $seller_id = $_SESSION['user_id'];
    $sql = "INSERT INTO products (seller_id, name, description, price, category, image, stock, is_featured) 
            VALUES ('$seller_id', '$name', '$description', '$price', '$category', '$image', '$stock', 0)";
    if ($conn->query($sql) === TRUE) {
        echo '<script>redirect("products.php");</script>';
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product - Etsy Clone</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background: #f9f9f9; color: #333; }
        .container { max-width: 500px; margin: 50px auto; padding: 20px; background: white; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        h2 { color: #ff6f61; text-align: center; }
        .form-group { margin: 15px 0; }
        .form-group label { display: block; font-size: 18px; margin-bottom: 5px; }
        .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 16px; }
        .btn { background: #ff6f61; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; width: 100%; }
        .btn:hover { background: #e65b50; }
        @media (max-width: 600px) { .container { margin: 20px; padding: 15px; } }
    </style>
</head>
<body>
    <div class="container">
        <h2>Add Product</h2>
        <form method="POST">
            <div class="form-group">
                <label for="name">Product Name</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" required></textarea>
            </div>
            <div class="form-group">
                <label for="price">Price ($)</label>
                <input type="number" id="price" name="price" step="0.01" required>
            </div>
            <div class="form-group">
                <label for="category">Category</label>
                <select id="category" name="category">
                    <option value="Handmade">Handmade</option>
                    <option value="Jewelry">Jewelry</option>
                    <option value="Home Decor">Home Decor</option>
                    <option value="Digital Products">Digital Products</option>
                </select>
            </div>
            <div class="form-group">
                <label for="stock">Stock</label>
                <input type="number" id="stock" name="stock" required>
            </div>
            <div class="form-group">
                <label for="image">Image URL</label>
                <input type="text" id="image" name="image" placeholder="Enter image URL" required>
            </div>
            <button type="submit" class="btn">Add Product</button>
        </form>
    </div>
    <script>
        function redirect(url) {
            window.location.href = url;
        }
    </script>
</body>
</html>
<?php $conn->close(); ?>
