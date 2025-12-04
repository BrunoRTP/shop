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
    
    $id = $_POST['id'];
    
    if($_SESSION['type_client'] != 'admin' && $_SESSION['user_id'] != $id){
        echo "No tienes permiso para editar este cliente.";
        exit;
    }
    
    $sql = "SELECT * FROM 025_customers WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    $customer = mysqli_fetch_assoc($result);
    
    if(!$customer){
        echo "Cliente no encontrado.";
        exit;
    }
    
    mysqli_close($conn);
?>
<div class="container" style="padding: 20px;">
    <h2>Actualizar Cliente</h2>
    
    <form action="/student025/shop/backend/db/bd_customers_update.php" method="POST">
        
        <label for="username">Nombre de Usuario:</label>
        <input type="text" name="username" value="<?= $customer['username'] ?? '' ?>" pattern="[a-zA-Z0-9_@.]+" title="Solo letras, números y guiones bajos están permitidos." required><br><br>

        <label for="password">Nueva Contraseña (dejar en blanco para mantener la actual):</label>
        <input type="password" name="password" minlength="6"><br><br>

        <?php if($_SESSION['type_client'] == 'admin'): ?>
        <label for="type_client">Tipo de Cliente:</label>
        <select name="type_client" required>
            <option value="guest" <?= (($customer['type_client'] ?? '') == 'guest') ? 'selected' : '' ?>>Guest</option>
            <option value="admin" <?= (($customer['type_client'] ?? '') == 'admin') ? 'selected' : '' ?>>Admin</option>
        </select><br><br>
        <?php else: ?>
        <input type="hidden" name="type_client" value="<?= $customer['type_client'] ?? 'guest' ?>">
        <?php endif; ?>

        <input type="hidden" name="id" value="<?= $customer['id'] ?? '' ?>">

        <button type="submit" name="update_customer">Guardar Cliente</button>
        
        <a href="/student025/shop/backend/customers.php" style="margin-left: 10px;">Cancelar</a>
    </form>
</div>

<?php include($root_dir . 'footer.php'); ?>