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
    
    $id = $_POST['id'];
?>
<div class="container" style="padding: 20px;">
    <h2>Confirmación</h2>
    
    <form action="/student025/shop/backend/db/bd_customers_delete.php" method="POST">
        
        <label for="id">¿Estás seguro de eliminar este cliente?</label><br><br>        
        <input type="hidden" name="id" value="<?= $id ?? '' ?>">
        <button type="submit" name="delete_customer">Aceptar</button>
        
        <a href="/student025/shop/backend/customers.php" style="margin-left: 10px;">Cancelar</a>
    </form>
</div>

<?php include($root_dir . 'footer.php'); ?>