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
    $product_id = isset($_GET['id']) ? $_GET['id'] : null;
    
    if($product_id){
        $sql = "DELETE FROM 025_cart 
                WHERE customer_id = $customer_id AND product_id = $product_id";
        
        $result = mysqli_query($conn, $sql);
        
        if($result){
            if (mysqli_affected_rows($conn) > 0) {
                echo "¡Producto eliminado del carrito exitosamente!";
                echo "<br><a href='/student025/shop/backend/cart.php'>Volver al carrito</a>";
            } else {
                echo "No se encontró el producto en tu carrito.";
                echo "<br><a href='/student025/shop/backend/cart.php'>Volver al carrito</a>";
            }
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        echo "ID de producto no especificado.";
        echo "<br><a href='/student025/shop/backend/cart.php'>Volver al carrito</a>";
    }
    
    mysqli_close($conn);
?>

<?php include($root_dir . 'footer.php'); ?>