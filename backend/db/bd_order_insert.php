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
    
    if(isset($_POST['insert_order'])){
        $customer_id = $_POST['customer_id'];
        $product_id = $_POST['product_id'];
        $quantity = $_POST['quantity'] ?? 1;
        
        // Verificar que el customer_id coincida con el usuario de la sesión
        if($customer_id != $_SESSION['user_id']){
            echo "Error: No puedes crear pedidos para otros usuarios.";
            exit;
        }
        
        // Obtener el precio del producto de la base de datos
        $sql_price = "SELECT price FROM 025_products WHERE id = $product_id";
        $result_price = mysqli_query($conn, $sql_price);
        $product = mysqli_fetch_assoc($result_price);
        
        // Calcular el precio total
        $price = $product['price'] * $quantity;

        $sql = "INSERT INTO 025_order (customer_id, product_id, quantity, price) 
                VALUES ('$customer_id', '$product_id', '$quantity', '$price')";

        // Ejecutar consulta
        $result = mysqli_query($conn, $sql);
        
        // Confirm that all works
        if($result){
            if (mysqli_affected_rows($conn) > 0) {
                echo "¡Actualización exitosa! Se añadió el pedido";
            } else {
                echo "Actualización terminada, pero no se añadió ninguna fila.";
            }
        }else{
            echo "Error en algún lado: " . mysqli_error($conn);
        }
        unset($_POST["insert_order"]);
    }
    mysqli_close($conn);
?>
<?php include($root_dir . 'footer.php'); ?>