<?php
session_start();
include 'bd.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Cart</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f0f5;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 50px 20px;
        }
        .container {
            background: #fff;
            border-radius: 20px;
            padding: 40px 35px;
            width: 100%;
            max-width: 650px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.08);
        }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; }
        h1 { color: #333; font-size: 26px; font-weight: 700; }
        .back-link { color: #667eea; text-decoration: none; font-size: 14px; font-weight: 600; }
        .back-link:hover { text-decoration: underline; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th { text-align: left; padding: 14px 12px; border-bottom: 2px solid #e8e8e8; color: #aaa; font-size: 13px; font-weight: 600; text-transform: uppercase; }
        td { padding: 16px 12px; border-bottom: 1px solid #f0f0f0; vertical-align: middle; }
        .product-cell { font-weight: 600; color: #333; font-size: 15px; }
        .price-cell { color: #667eea; font-weight: 700; font-size: 15px; }
        .qty-input { width: 65px; padding: 8px; border: 2px solid #ddd; border-radius: 10px; text-align: center; font-size: 15px; font-weight: 600; color: #333; outline: none; }
        .btn-update { padding: 8px 14px; background: #fff; color: #667eea; border: 2px solid #667eea; border-radius: 10px; font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.3s; }
        .btn-update:hover { background: #667eea; color: #fff; }
        .btn-remove { padding: 8px 20px; background: #fff; color: #333; border: 2px solid #ddd; border-radius: 10px; font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.3s; }
        .btn-remove:hover { background: #ff6b6b; color: #fff; border-color: #ff6b6b; }
        .total { text-align: left; font-size: 18px; font-weight: 700; color: #333; margin: 20px 0 25px; padding-top: 15px; border-top: 2px solid #e8e8e8; }
        .total span { color: #667eea; }
        .btn-confirm { display: block; width: 100%; padding: 16px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; border: none; border-radius: 14px; font-size: 17px; font-weight: 600; cursor: pointer; transition: all 0.3s; }
        .btn-confirm:hover { opacity: 0.9; transform: translateY(-2px); }
        .empty-cart { text-align: center; padding: 30px 0; color: #888; }
        .empty-cart a { color: #667eea; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>My Cart</h1>
            <a href="index.php" class="back-link">&larr; Back to shop</a>
        </div>

        <?php if (empty($_SESSION['cart'])): ?>
            <div class="empty-cart">
                <p>Votre panier est vide.</p>
                <a href="index.php">Retour au shop</a>
            </div>

        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>QTY</th>
                        <th>Price</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $total = 0;
                    foreach ($_SESSION['cart'] as $product_id => $quantity):
                        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id");
                        $stmt->execute([':id' => $product_id]);
                        $product = $stmt->fetch();
                        $line_total = $product['price'] * $quantity;
                        $total += $line_total;
                    ?>
                    <tr>
                        <td class="product-cell"><?php echo $product['name']; ?></td>

                        <td>
                            <form action="update_cart.php" method="POST" style="display:flex;gap:6px;align-items:center;">
                                <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                                <input type="number" name="quantity" class="qty-input" value="<?php echo $quantity; ?>" min="0">
                                <button type="submit" class="btn-update">Update</button>
                            </form>
                        </td>

                        <td class="price-cell">$<?php echo number_format($line_total, 2); ?></td>

                        <td>
                            <a href="remove_from_cart.php?id=<?php echo $product_id; ?>">
                                <button class="btn-remove">Remove</button>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="total">Total: <span>$<?php echo number_format($total, 2); ?></span></div>

            <form action="checkout.php" method="POST">
                <button type="submit" class="btn-confirm">Confirm order</button>
            </form>

        <?php endif; ?>
    </div>
</body>
</html>