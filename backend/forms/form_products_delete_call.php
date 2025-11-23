<?php
    $root_dir = $_SERVER['DOCUMENT_ROOT']  . '/student025/shop/backend/';
    include($root_dir . 'auth_functions.php');
    require_login();
    include($root_dir . 'header.php'); 
    include($root_dir . 'db_connection.php'); 
?>
<?php
    $id = isset($_GET['id']) ? $_GET['id'] : '';

?>

<div class="container" style="padding: 20px;">
    <h2>Id del Producto</h2>
    
    <form action="/student025/shop/backend/forms/form_products_delete.php" method="POST">
        
        <label for="name">Dame el id del producto a eliminar</label>        
        <input type="text" name="id" value="<?php echo $id; ?>" required><br><br>

        <button type="submit" name="delete_product">Aceptar</button>
        
        <a href= "/student025/shop/backend/products.php" style="margin-left: 10px;">Cancelar</a>
    </form>
</div>

<?php include($root_dir . 'footer.php'); ?>