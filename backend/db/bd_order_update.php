<?php
session_start();
$root_dir = $_SERVER['DOCUMENT_ROOT'] . '/student025/shop/backend/';
include($root_dir. 'header.php'); 
include($root_dir . 'db_connection.php'); 
?>

<?php
    // Verificar que el usuario esté logueado
    if(!isset($_SESSION['user_id'])){
        header("Location: /student025/shop/backend/form_login.php");     
        exit; 
    }
    
    if(isset($_POST['update_order'])){
        $id = $_POST['id'];
        $customer_id = $_POST['customer_id'];
        $product_id = $_POST['product_id'];
        $quantity = $_POST['quantity'] ?? 1;
        
        // Si no es admin, verificar que sea su propio pedido
        if($_SESSION['type_client'] != 'admin' && $customer_id != $_SESSION['user_id']){
            echo "No tienes permiso para editar este pedido.";
            exit;
        }
        
        // Obtener el precio del producto de la base de datos
        $sql_price = "SELECT price FROM 025_products WHERE id = $product_id";
        $result_price = mysqli_query($conn, $sql_price);
        $product = mysqli_fetch_assoc($result_price);
        
        // Calcular el precio total
        $price = $product['price'] * $quantity;
        
        $sql = "UPDATE 025_order 
                SET customer_id = '$customer_id', 
                    product_id = '$product_id', 
                    quantity = $quantity, 
                    price = $price 
                WHERE id = $id";

        $result = mysqli_query($conn, $sql);
            
        if($result){
            if (mysqli_affected_rows($conn) > 0) {
                echo "¡Actualización exitosa! Se modificó " . mysqli_affected_rows($conn) . " pedido(s).";
            } else {
                echo "Actualización terminada, pero no se modificó ninguna fila.";
            }
        }else{
            echo "Error en algún lado: " . mysqli_error($conn);
        }
    }
    mysqli_close($conn);
?>
<?php include($root_dir . 'footer.php'); ?>