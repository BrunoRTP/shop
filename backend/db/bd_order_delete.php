<?php
$root_dir = $_SERVER['DOCUMENT_ROOT'] . '/student025/shop/backend/';
include($root_dir. 'header.php'); 
include($root_dir . 'db_connection.php'); 
?>

<?php
    if(isset($_POST['delete_order'])){
        $id = $_POST['id'];
        $sql = "DELETE FROM 025_order 
                WHERE id = $id";

        $result = mysqli_query($conn, $sql);
            
        if($result){
            if (mysqli_affected_rows($conn) > 0) {
                echo "¡Actualización exitosa! Se eliminó el pedido";
            } else {
                echo "Actualización terminada, pero no se eliminó ninguna fila.";
            }
        }else{
            echo "Error en algún lado: " . mysqli_error($conn);
        }
    }
    mysqli_close($conn);
?>
<?php include($root_dir . 'footer.php'); ?>