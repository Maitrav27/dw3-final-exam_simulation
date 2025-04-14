<?php
require 'session.php';
require 'db.php';

if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit;
}

$product_id = $_GET['id'];
$user_id = $_SESSION['user_id'];


$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ? AND user_id = ?");
$stmt->execute([$product_id, $user_id]);
$product = $stmt->fetch();

if ($product) {
    if (!empty($product['image_path']) && file_exists($product['image_path'])) {
        unlink($product['image_path']);
    }


    $delStmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $delStmt->execute([$product_id]);
}

header("Location: dashboard.php");
exit;
