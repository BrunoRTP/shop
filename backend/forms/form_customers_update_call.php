<?php
    session_start();
    $root_dir = $_SERVER['DOCUMENT_ROOT']  . '/student025/shop/backend/';
    include($root_dir . 'header.php'); 
?>
<?php
    if(!isset($_SESSION['user_id'])){
        header("Location: /student025/shop/backend/form_login.php");     
        exit; 
    }
    
    $id = isset($_GET['id']) ? $_GET['id'] : '';
?>

<div class="container" style="padding: 20px;">
    <h2>Actualizar Cliente</h2>
    
    <form action="/student025/shop/backend/forms/form_customers_update.php" method="POST">
        
        <label for="id">ID del Cliente:</label>
        <input type="text" name="id" value="<?php echo htmlspecialchars($id); ?>" pattern="[0-9]+" title="Solo números están permitidos." required><br><br>

        <button type="submit" name="id_update_customer">Aceptar</button>
        
        <a href="/student025/shop/backend/customers.php" style="margin-left: 10px;">Cancelar</a>
    </form>
</div>

<?php include($root_dir . 'footer.php'); ?>