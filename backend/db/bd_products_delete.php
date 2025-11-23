<?php
$root_dir = $_SERVER['DOCUMENT_ROOT'] . '/student025/shop/backend/';
include($root_dir. 'header.php'); 
include($root_dir . 'db_connection.php'); 
?>

<?php
    if(isset($_POST['delete_product'])){
        $id = $_POST['id'];
        $sql = "DELETE FROM 025_products 
                WHERE id = $id";

        $result = mysqli_query($conn, $sql);
            
        if($result){
            if (mysqli_affected_rows($conn) > 0) {
                echo "¡Actualización exitosa! Se elimino";
            } else {
                echo "Actualización terminada, pero no se elimino ninguna fila.";
            }
        }else{
            echo "Error en algun lado: " . mysqli_error($conn);
        }
    }
?>
<?php include($root_dir . 'footer.php'); ?>