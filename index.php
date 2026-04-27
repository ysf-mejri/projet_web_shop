<?php
session_start();
include 'bd.php';

$stmt = $pdo->query("SELECT * FROM products");
$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Shop</title>
    <style>
        * { margin: 0; padding: 5; box-sizing: border-box; }
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
            max-width: 500px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.08);
        }
        h1 { text-align: center; color: #333; margin-bottom: 30px; font-size: 26px; font-weight: 700; }
        .product-card {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 18px 20px;
            margin-bottom: 14px;
            border: 2px solid #e8e8e8;
            border-radius: 14px;
            background: #fafafa;
        }
        .product-info { flex: 1; }
        .product-name { font-size: 17px; font-weight: 600; color: #333; margin-bottom: 3px; }
        .product-price { font-size: 14px; color: #999; }
        .qty-input {
            width: 65px;
            padding: 10px;
            border: 2px solid #ddd;
            border-radius: 10px;
            text-align: center;
            font-size: 16px;
            font-weight: 600;
            color: #333;
            outline: none;
        }
        .btn-cart {
            display: block;
            width: 100%;
            padding: 16px;
            margin-top: 25px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            border: none;
            border-radius: 14px;
            font-size: 17px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        .btn-cart:hover { opacity: 0.9; transform: translateY(-2px); }
        .empty-msg { color: #ff6b6b; font-size: 13px; text-align: center; margin-top: 10px; display: none; }
    </style>
</head>
<body>
    <div class="container">
        <h1>My Shop</h1>

        <form action="add_to_cart.php" method="POST" id="shopForm">
            <?php foreach ($products as $product): ?>
            <div class="product-card">
                <div class="product-info">
                    <div class="product-name"><?php echo $product['name']; ?></div>
                    <div class="product-price">$<?php echo $product['price']; ?></div>
                </div>
                <input type="number" 
                       name="quantities[<?php echo $product['id']; ?>]" 
                       class="qty-input" 
                       value="0" 
                       min="0">
            </div>
            <?php endforeach; ?>

            <button type="submit" class="btn-cart">Go to cart</button>
            <div class="empty-msg" id="emptyMsg">Veuillez choisir au moins un produit!</div>
        </form>
    </div>

    <script>
        document.getElementById('shopForm').addEventListener('submit', function(e) {
            const inputs = document.querySelectorAll('.qty-input');
            let hasSelection = false;
            inputs.forEach(input => {
                if (parseInt(input.value) > 0) hasSelection = true;
            });
            if (!hasSelection) {
                e.preventDefault();
                document.getElementById('emptyMsg').style.display = 'block';
            }
        });
    </script>
</body>
</html>