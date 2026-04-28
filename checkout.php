<?php
session_start();
include 'bd.php';

if (empty($_SESSION['cart'])) {
    header('Location: cart.php');
    exit();
}

$total = 0;
$cart  = $_SESSION['cart'];

foreach ($cart as $product_id => $quantity) {
    $stmt = $pdo->prepare("SELECT price FROM products WHERE id = :id");
    $stmt->execute([':id' => $product_id]);
    $product = $stmt->fetch();
    $total  += $product['price'] * $quantity;
}

$stmt = $pdo->prepare("INSERT INTO orders (total) VALUES (:total)");
$stmt->execute([':total' => $total]);
$order_id = $pdo->lastInsertId();

foreach ($cart as $product_id => $quantity) {
    $stmt = $pdo->prepare("SELECT price FROM products WHERE id = :id");
    $stmt->execute([':id' => $product_id]);
    $product = $stmt->fetch();

    $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, unit_price) 
                           VALUES (:order_id, :product_id, :quantity, :unit_price)");
    $stmt->execute([
        ':order_id'   => $order_id,
        ':product_id' => $product_id,
        ':quantity'   => $quantity,
        ':unit_price' => $product['price']
    ]);

    $stmt = $pdo->prepare("UPDATE products SET stock = stock - :quantity WHERE id = :id");
    $stmt->execute([
        ':quantity' => $quantity,
        ':id'       => $product_id
    ]);
}

unset($_SESSION['cart']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmed</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f0f5;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
        }
        .container {
            background: #fff;
            border-radius: 20px;
            padding: 60px 50px;
            width: 100%;
            max-width: 480px;
            text-align: center;
            box-shadow: 0 10px 40px rgba(0,0,0,0.08);
        }
        .check-icon {
            width: 75px;
            height: 75px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto 25px;
        }
        .check-icon::after { content: '\2713'; color: #fff; font-size: 36px; font-weight: bold; }
        h1 { color: #333; font-size: 24px; font-weight: 700; margin-bottom: 12px; }
        .subtitle { color: #555; font-size: 17px; font-weight: 600; margin-bottom: 8px; }
        .message { color: #888; font-size: 15px; margin-bottom: 35px; }
        .back-link { display: inline-block; color: #667eea; text-decoration: none; font-size: 14px; font-weight: 600; }
        .back-link:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <div class="check-icon"></div>
        <h1>Order confirmed</h1>
        <div class="subtitle">Thank you for your order!</div>
        <div class="message">Your order has been saved successfully.</div>
        <a href="index.php" class="back-link">&larr; Back to shop</a>
    </div>
</body>
</html>