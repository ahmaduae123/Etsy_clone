<?php
session_start();
include 'db.php';
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$category = isset($_GET['category']) ? $conn->real_escape_string($_GET['category']) : '';
$price_min = isset($_GET['price_min']) ? $conn->real_escape_string($_GET['price_min']) : 0;
$price_max = isset($_GET['price_max']) ? $conn->real_escape_string($_GET['price_max']) : 9999;
$sql = "SELECT * FROM products WHERE name LIKE '%$search%' AND price BETWEEN $price_min AND $price_max";
if ($category) $sql .= " AND category = '$category'";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - Etsy Clone</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background: #f9f9f9; color: #333; }
        .header { background: #ff6f61; color: white; padding: 20px; text-align: center; }
        .nav { background: #333; padding: 10px; }
        .nav a { color: white; text-decoration: none; margin: 0 15px; font-size: 18px; }
        .nav a:hover { color: #ff6f61; }
        .search-bar { padding: 20px; text-align: center; }
        .search-bar input, .search-bar select { padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 16px; margin: 0 10px; }
        .btn { background: #ff6f61; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; }
        .btn:hover { background: #e65b50; }
        .product-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; padding: 20px; }
        .product { background: white; border-radius: 10px; padding: 15px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); text-align: center; }
        .product img { max-width: 100%; height: auto; border-radius: 5px; }
        .product h3 { color: #ff6f61; margin: 10px 0; }
        .product p { font-size: 16px; color: #555; }
        @media (max-width: 600px) { .product-grid { grid-template-columns: 1fr; } .search-bar input, .search-bar select { width: 100%; margin: 10px 0; } }
    </style>
</head>
<body>
    <div class="header">
        <h1>Products</h1>
    </div>
    <div class="nav">
        <a href="#" onclick="redirect('index.php')">Home</a>
        <a href="#" onclick="redirect('cart.php')">Cart</a>
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'seller') { ?>
            <a href="#" onclick="redirect('add_product.php')">Add Product</a>
        <?php } ?>
        <?php if (isset($_SESSION['user'])) { ?>
            <a href="#" onclick="redirect('logout.php')">Logout</a>
        <?php } ?>
    </div>
    <div class="search-bar">
        <input type="text" id="search" value="<?php echo $search; ?>" placeholder="Search products...">
        <select id="category">
            <option value="">All Categories</option>
            <option value="Handmade" <?php if ($category == 'Handmade') echo 'selected'; ?>>Handmade</option>
            <option value="Jewelry" <?php if ($category == 'Jewelry') echo 'selected'; ?>>Jewelry</
