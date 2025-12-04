<?php
    session_start();
    $root_dir = $_SERVER['DOCUMENT_ROOT']  . '/student025/shop/backend/';
    include($root_dir . 'header.php'); 
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
    
    mysqli_close($conn);
?>

<div class="container" style="padding: 20px;">
    <h2>Añadir Nuevo Cliente</h2>
    
    <form action="/student025/shop/backend/db/bd_customers_insert.php" method="POST">
        
        <label for="username">Nombre de Usuario:</label>
        <input type="text" name="username" pattern="[a-zA-Z0-9_@.]+" title="Solo letras, números y guiones bajos están permitidos." required><br><br>

        <label for="password">Contraseña:</label>
        <input type="password" name="password" minlength="4" required><br><br>

        <label for="type_client">Tipo de Cliente:</label>
        <select name="type_client" required>
            <option value="guest">Guest</option>
            <option value="admin">Admin</option>
        </select><br><br>

        <button type="submit" name="insert_customer">Guardar Cliente</button>
        
        <a href="/student025/shop/backend/customers.php" style="margin-left: 10px;">Cancelar</a>
    </form>
</div>

<?php include($root_dir . 'footer.php'); ?>