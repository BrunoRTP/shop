<?php
session_start(); // Iniciar sesión para guardar datos del usuario
$root_dir = $_SERVER['DOCUMENT_ROOT'] . '/student025/shop/backend/';
include($root_dir. 'header.php'); 
include($root_dir . 'db_connection.php'); 
?>

<?php
    if(isset($_POST['btnLog'])){
        $username = $_POST['username'];
        $password = $_POST['password'];

        $username = mysqli_real_escape_string($conn, $username);
        
        // Buscar el usuario por username
        $sql = "SELECT * FROM 025_customers WHERE username = '$username'";
        $result = mysqli_query($conn, $sql);
        
        if($result){
            if(mysqli_num_rows($result) > 0){
                $user = mysqli_fetch_assoc($result);
                
                if(password_verify($password, $user['password_hash'])){
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['type_client'] = $user['type_client'];
                    
                    echo "¡Inicio de sesión exitoso! Bienvenido " . htmlspecialchars($user['username']);

                    if($user['type_client'] == 'admin'){
                        header("Location: /student025/shop/backend/products.php");
                    } else {
                        header("Location: /student025/shop/backend/index.php");
                    }
                    exit();
                } 
                else if($user['password_hash'] === hash('sha256', $password)){
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['type_client'] = $user['type_client'];
                    
                    echo "¡Inicio de sesión exitoso! Bienvenido " . htmlspecialchars($user['username']);

                    if($user['type_client'] == 'admin'){
                        header("Location: /student025/shop/backend/products.php");
                    } else {
                        header("Location: /student025/shop/backend/index.php");
                    }
                    exit();
                } else {
                    echo "Usuario o contraseña incorrectos. Por favor, inténtalo de nuevo.";
                }
            } else {
                echo "Usuario o contraseña incorrectos. Por favor, inténtalo de nuevo.";
            }
        } else {
            echo "Error en la consulta: " . mysqli_error($conn);
        }
        
        mysqli_close($conn);
    }
?>

<?php include($root_dir . 'footer.php'); ?>