
<?php
$root_dir = $_SERVER['DOCUMENT_ROOT'] . '/student025/shop/backend/';
include($root_dir . 'auth_functions.php');
require_login();
include($root_dir. 'header.php'); 
include($root_dir . 'db_connection.php'); 
?>

<?php
    if(isset($_POST['insert_product'])){
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $description = mysqli_real_escape_string($conn, $_POST['description']);
        $quantity_available = $_POST['quantity_available'] ?? 0;
        $price = $_POST['price'] ?? 0;
        $category_id = $_POST['category_id'] ?? 1;
        
        // Procesar imagen si existe
        $image_data = null;
        if(isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
            $image_data = mysqli_real_escape_string($conn, file_get_contents($_FILES['product_image']['tmp_name']));
        }
        
        // SQL con imagen condicional
        $sql = "INSERT INTO 025_products (name, description, stock, price, category_id" . 
               ($image_data ? ", image" : "") . ") 
                VALUES ('$name', '$description', $quantity_available, $price, $category_id" . 
               ($image_data ? ", '$image_data'" : "") . ")";
        
        $result = mysqli_query($conn, $sql);
        
        if($result){
            if(mysqli_affected_rows($conn) > 0) {
                echo "¡Actualización exitosa! Se añadió el producto";
            } else {
                echo "Actualización terminada, pero no se añadió ninguna fila.";
            }
        } else {
            echo "Error en algún lado: " . mysqli_error($conn);
        }
        
        unset($_POST["insert_product"]);
    }
    mysqli_close($conn);
?>

<?php include($root_dir . 'footer.php'); ?>