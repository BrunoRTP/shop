<?php
    $root_dir = $_SERVER['DOCUMENT_ROOT']  . '/student025/shop/backend/';
    include($root_dir . 'auth_functions.php');
    require_login();
    include($root_dir . 'header.php'); 
?>
<?php
    $id = $_POST['id'];
?>
<div class="container" style="padding: 20px;">
    <h2>Confirmacion</h2>
    
    <form action="/student025/shop/backend/db/bd_products_delete.php" method="POST">
        
        <label for="name">Estas seguro?</label><br><br>        
        <input type="hidden" name="id" value="<?= $id ?? '' ?>">
        <button type="submit" name="delete_product">Aceptar</button>
        
        <a href= "/student025/shop/backend/products.php" style="margin-left: 10px;">Cancelar</a>
    </form>
</div>

<?php include($root_dir . 'footer.php'); ?>