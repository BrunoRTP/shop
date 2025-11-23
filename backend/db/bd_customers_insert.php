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
    
    if($_SESSION['type_client'] != 'admin'){
        echo "Solo los administradores pueden crear nuevos usuarios.";
        exit;
    }
    
    if(isset($_POST['insert_customer'])){
        $username = $_POST['username'];
        $password = $_POST['password'];
        $type_client = $_POST['type_client'] ?? 'guest';
        
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO 025_customers (username, password_hash, type_client) 
                VALUES ('$username', '$password_hash', '$type_client')";

        $result = mysqli_query($conn, $sql);
        
        if($result){
            if (mysqli_affected_rows($conn) > 0) {
                echo "¡Actualización exitosa! Se añadió el cliente";
            } else {
                echo "Actualización terminada, pero no se añadió ninguna fila.";
            }
        }else{
            echo "Error en algún lado: " . mysqli_error($conn);
        }
        unset($_POST["insert_customer"]);
    }

?>
<?php include($root_dir . 'footer.php'); ?>