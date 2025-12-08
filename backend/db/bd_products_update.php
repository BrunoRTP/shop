<?php
$root_dir = $_SERVER['DOCUMENT_ROOT'] . '/student025/shop/backend/';
include($root_dir. 'header.php'); 
include($root_dir . 'db_connection.php'); 
?>

<?php
    if(isset($_POST['update_product'])){
        $id = $_POST['id'];;
        $name = $_POST['name'];
        $description = $_POST['description'];
        $quantity_available = $_POST['quantity_available'] ?? 0;
        $price = $_POST['price'] ?? 0;
        $category_id = $_POST['category_id'] ?? 1;
        $sql = "UPDATE 025_products 
                SET name = '$name', 
                    description = '$description', 
                    quantity_available = '$quantity_available', 
                    price = '$price', 
                    category_id = '$category_id' 
                WHERE id = '$id'";

        $result = mysqli_query($conn, $sql);
            
        if($result){
            if (mysqli_affected_rows($conn) > 0) {
                echo "¡Actualización exitosa! Se modificó " . mysqli_affected_rows($conn) . " producto(s).";
            } else {
                echo "Actualización terminada, pero no se modificó ninguna fila.";
            }
        }else{
            echo "Error en algun lado: " . mysqli_error($conn);
        }
    }
    mysqli_close($conn);
?>
<?php include($root_dir . 'footer.php'); ?>