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
    
    if(isset($_POST['update_customer'])){
        $id = $_POST['id'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $type_client = $_POST['type_client'] ?? 'guest';
        
        if($_SESSION['type_client'] != 'admin' && $_SESSION['user_id'] != $id){
            echo "No tienes permiso para editar este cliente.";
            exit;
        }
        
        if(!empty($password)){
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $sql = "UPDATE 025_customers 
                    SET username = '$username', 
                        password_hash = '$password_hash', 
                        type_client = '$type_client' 
                    WHERE id = $id";
        } else {
            $sql = "UPDATE 025_customers 
                    SET username = '$username', 
                        type_client = '$type_client' 
                    WHERE id = $id";
        }

        $result = mysqli_query($conn, $sql);
            
        if($result){
            if (mysqli_affected_rows($conn) > 0) {
                echo "¡Actualización exitosa! Se modificó el cliente.";
                
                if($_SESSION['user_id'] == $id){
                    $_SESSION['username'] = $username;
                    $_SESSION['type_client'] = $type_client;
                }
            } else {
                echo "Actualización terminada, pero no se modificó ninguna fila.";
            }
        }else{
            echo "Error en algún lado: " . mysqli_error($conn);
        }
    }
?>
<?php include($root_dir . 'footer.php'); ?>