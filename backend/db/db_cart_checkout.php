<?php
session_start();
$root_dir = $_SERVER['DOCUMENT_ROOT'] . '/student025/shop/backend/';
include($root_dir. 'header.php'); 
include($root_dir . 'db_connection.php'); 
?>

<?php
    if(!isset($_SESSION['user_id'])){
        header("Location: /student025/shop/backend/form_login.php");     
        exit; 
    }
    
    $customer_id = $_SESSION['user_id'];
    
    $sql = "SELECT c.product_id, c.quantity, p.price 
            FROM 025_cart c
            JOIN 025_products p ON c.product_id = p.id
            WHERE c.customer_id = $customer_id";
    
    $result = mysqli_query($conn, $sql);
    
    if(mysqli_num_rows($result) == 0){
        echo "Tu carrito está vacío. No hay nada que procesar.";
        echo "<br><a href='/student025/shop/backend/products.php'>Ver productos</a>";
        exit;
    }
    
    $orders_created = 0;
    $errors = [];
    
    while($item = mysqli_fetch_assoc($result)){
        $product_id = $item['product_id'];
        $quantity = $item['quantity'];
        $price = $item['price'] * $quantity;
        
        $sql_insert = "INSERT INTO 025_order (customer_id, product_id, quantity, price) 
                       VALUES ($customer_id, $product_id, $quantity, $price)";
        
        if(mysqli_query($conn, $sql_insert)){
            $orders_created++;
        } else {
            $errors[] = "Error al crear pedido para producto ID $product_id: " . mysqli_error($conn);
        }
    }
    
    if($orders_created > 0 && empty($errors)){
        $sql_clear = "DELETE FROM 025_cart WHERE customer_id = $customer_id";
        mysqli_query($conn, $sql_clear);
        
        echo "<h2>¡Pedido completado exitosamente!</h2>";
        echo "<p>Se crearon $orders_created pedido(s).</p>";
        echo "<p>Tu carrito ha sido vaciado.</p>";
        echo "<br><a href='/student025/shop/backend/orders.php'>Ver mis pedidos</a>";
        echo " | <a href='/student025/shop/backend/products.php'>Seguir comprando</a>";
    } else {
        echo "<h2>Hubo problemas al procesar tu pedido</h2>";
        echo "<p>Pedidos creados: $orders_created</p>";
        if(!empty($errors)){
            echo "<p>Errores:</p><ul>";
            foreach($errors as $error){
                echo "<li>$error</li>";
            }
            echo "</ul>";
        }
        echo "<br><a href='/student025/shop/backend/cart.php'>Volver al carrito</a>";
    }
    
    mysqli_close($conn);
?>

<?php include($root_dir . 'footer.php'); ?>