<?php
$root_dir = $_SERVER['DOCUMENT_ROOT'] . '/student025/shop/backend/';
include($root_dir . 'db_connection.php'); 
?>
<?php
    session_start(); 
    $product_id = isset($_POST['id']) ? $_POST['id'] : (isset($_GET['id']) ? $_GET['id'] : null);
    $customer_id = $_SESSION['user_id'];
    
    $sql_check = "SELECT quantity FROM 025_cart 
                  WHERE customer_id = $customer_id AND product_id = $product_id";
    
    $result_check = mysqli_query($conn, $sql_check);
    
    if(mysqli_num_rows($result_check) > 0) {
        $sql = "UPDATE 025_cart 
                SET quantity = quantity + 1 
                WHERE customer_id = $customer_id AND product_id = $product_id";
        
        $result = mysqli_query($conn, $sql);
        
        if($result){
            if (mysqli_affected_rows($conn) > 0) {
                echo "¡Actualización exitosa! Se incrementó la cantidad.";
            } else {
                echo "Actualización terminada, pero no se modificó ninguna fila.";
            }
        } else {
            echo "Error en algun lado: " . mysqli_error($conn);
        }
        
    } else {
        $sql = "INSERT INTO 025_cart (customer_id, product_id, quantity) 
                VALUES ($customer_id, $product_id, 1)";
        
        $result = mysqli_query($conn, $sql);
        
        if($result){
            if (mysqli_affected_rows($conn) > 0) {
                echo "¡Actualización exitosa! Se añadió el producto al carrito.";
            } else {
                echo "Actualización terminada, pero no se añadió ninguna fila.";
            }
        } else {
            echo "Error en algun lado: " . mysqli_error($conn);
        }
    }
    
    mysqli_close($conn);
    header("Location: /student025/shop/backend/cart.php");     
?>