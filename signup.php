<?php
session_start();
include 'db.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $conn->real_escape_string($_POST['role']);
    $sql = "INSERT INTO users (username, email, password, role) VALUES ('$username', '$email', '$password', '$role')";
    if ($conn->query($sql) === TRUE) {
        echo '<script>redirect("login.php");</script>';
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
    <title>Signup - Etsy Clone</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background: #f9f9f9; color: #333; }
        .container { max-width: 500px; margin: 50px auto; padding: 20px; background: white; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        h2 { color: #ff6f61; text-align: center; }
        .form-group { margin: 15px 0; }
        .form-group label { display: block; font-size: 18px; margin-bottom: 5px; }
        .form-group input, .form-group select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 16px; }
        .btn { background: #ff6f61; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; width: 100%; }
        .btn:hover { background: #e65b50; }
        @media (max-width: 600px) { .container { margin: 20px; padding: 15px; } }
    </style>
</head>
<body>
    <div class="container">
        <h2>Sign Up</h2>
        <form method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="role">Role</label>
                <select id="role" name="role">
                    <option value="buyer">Buyer</option>
                    <option value="seller">Seller</option>
                </select>
            </div>
            <button type="submit" class="btn">Sign Up</button>
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
