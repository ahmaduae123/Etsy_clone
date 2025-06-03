<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$host = 'localhost'; // Try this; replace with the correct hostname from your hosting provider
$user = 'urnrgaote95vf'; // Verify this in your hosting panel
$password = 'tgk9ztof7xb1'; // Verify this in your hosting panel
$dbname = 'dbz1rwqeii72dh'; // Verify this in your hosting panel

try {
    $conn = new mysqli($host, $user, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>
