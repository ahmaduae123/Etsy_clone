<?php
session_start();
session_destroy();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout - Etsy Clone</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background: #f9f9f9; color: #333; }
    </style>
</head>
<body>
    <script>
        function redirect(url) {
            window.location.href = url;
        }
        redirect('index.php');
    </script>
</body>
</html>
