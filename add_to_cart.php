<?php
session_start();

if (isset($_POST['quantities'])) {
    
    foreach ($_POST['quantities'] as $product_id => $quantity) {
        
        $quantity = (int)$quantity;
        
        if ($quantity > 0) {
            $_SESSION['cart'][$product_id] = $quantity;
        }
    }
}

header('Location: cart.php');
exit();
?>