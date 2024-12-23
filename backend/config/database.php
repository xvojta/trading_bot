<?php
$host = 'localhost';
$db = 'trading_bot';
$user = 'root'; // Default user for XAMPP
$pass = 'root';     // Default password for XAMPP

$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
try {
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}
?>