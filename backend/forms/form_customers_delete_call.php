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
    
    $id = isset($_GET['id']) ? $_GET['id'] : '';
?>

<div class="container" style="padding: 20px;">
    <h2>Id del Cliente</h2>
    
    <form action="/student025/shop/backend/forms/form_customers_delete.php" method="POST">
        
        <label for="id">Dame el id del cliente a eliminar</label>        
        <input type="text" name="id" value="<?php echo htmlspecialchars($id); ?>" pattern="[0-9]+" title="Solo números están permitidos." required><br><br>

        <button type="submit" name="delete_customer">Aceptar</button>
        
        <a href="/student025/shop/backend/customers.php" style="margin-left: 10px;">Cancelar</a>
    </form>
</div>
<?php
    mysqli_close($conn);
?>
<?php include($root_dir . 'footer.php'); ?>