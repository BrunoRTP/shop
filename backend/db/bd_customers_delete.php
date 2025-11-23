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
    
    if(isset($_POST['delete_customer'])){
        $id = $_POST['id'];
        
        if($_SESSION['type_client'] != 'admin' && $_SESSION['user_id'] != $id){
            echo "No tienes permiso para eliminar este cliente.";
            exit;
        }
        
        $sql = "DELETE FROM 025_customers 
                WHERE id = $id";

        $result = mysqli_query($conn, $sql);
            
        if($result){
            if (mysqli_affected_rows($conn) > 0) {
                echo "¡Actualización exitosa! Se eliminó el cliente";

                if($_SESSION['user_id'] == $id){
                    session_destroy();
                    echo "<br>Tu cuenta ha sido eliminada. Redirigiendo al login...";
                    header("refresh:3;url=/student025/shop/backend/form_login.php");
                }
            } else {
                echo "Actualización terminada, pero no se eliminó ninguna fila.";
            }
        }else{
            echo "Error en algún lado: " . mysqli_error($conn);
        }
    }
?>
<?php include($root_dir . 'footer.php'); ?>