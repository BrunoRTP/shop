<?php
$root_dir = $_SERVER['DOCUMENT_ROOT'] . '/student025/shop/backend/';
include($root_dir. 'header.php'); 
include($root_dir . 'db_connection.php'); 
?>

<?php
    if(isset($_POST['insert_product'])){
        $name = $_POST['name'];
        $description = $_POST['description'];
        $quantity_available = $_POST['quantity_available'] ?? 0;
        $price = $_POST['price'] ?? 0;
        $category_id = $_POST['category_id'] ?? 1;

        $sql = "INSERT INTO 025_products (name, description, stock, price, category_id) 
                VALUES ('$name', '$description', '$quantity_available', '$price', '$category_id')";

        // Ejecutar consulta
        $result = mysqli_query($conn, $sql);
        
        // Confirm that all works
        if($result){
            if (mysqli_affected_rows($conn) > 0) {
                echo "¡Actualización exitosa! Se añadio el producto";
            } else {
                echo "Actualización terminada, pero no se añadio ninguna fila.";
            }
        }else{
            echo "Error en algun lado: " . mysqli_error($conn);
        }
        unset($_POST["insert_product"]);
    }
    mysqli_close($conn);
?>
<?php include($root_dir . 'footer.php'); ?>