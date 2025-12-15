<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user']['id'];
    $productId = (int) ($_POST['product_id'] ?? 0);
    $quantity = (int) ($_POST['quantity'] ?? 1);
    if ($quantity < 1) $quantity = 1;

    // Check if product exists
    $stmt = $pdo->prepare("SELECT id FROM products WHERE id = :id");
    $stmt->execute(['id' => $productId]);
    if ($stmt->rowCount() === 0) {
        header("Location: shop.php");
        exit;
    }

    // Check if item already in cart
    $stmt = $pdo->prepare("SELECT id, quantity FROM cart_items WHERE user_id = :user_id AND product_id = :product_id");
    $stmt->execute([
        'user_id' => $userId,
        'product_id' => $productId
    ]);
    $existing = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existing) {
        $newQty = $existing['quantity'] + $quantity;
        $update = $pdo->prepare("UPDATE cart_items SET quantity = :qty WHERE id = :id");
        $update->execute(['qty' => $newQty, 'id' => $existing['id']]);
    } else {
        $insert = $pdo->prepare("INSERT INTO cart_items (user_id, product_id, quantity) VALUES (:user_id, :product_id, :quantity)");
        $insert->execute([
            'user_id' => $userId,
            'product_id' => $productId,
            'quantity' => $quantity
        ]);
    }

    header("Location: cart.php");
    exit;
}
header("Location: shop.php");
