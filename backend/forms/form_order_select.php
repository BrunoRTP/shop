<?php
    $root_dir = $_SERVER['DOCUMENT_ROOT']  . '/student025/shop/backend/';
    include($root_dir . 'auth_functions.php');
    require_login();
    include($root_dir . 'header.php'); 
?>
<div class="container" style="padding: 20px;">
    <h2>Confirmación</h2>
    
    <form action="/student025/shop/backend/db/bd_order_select.php" method="POST">
        
        <label for="id">¿Estás seguro?</label><br><br>        
        <input type="hidden" name="id" value="<?= $id ?? '' ?>">
        <button type="submit" name="select_order">Aceptar</button>
        
        <a href="/student025/shop/backend/orders.php" style="margin-left: 10px;">Cancelar</a>
    </form>
</div>

<?php include($root_dir . 'footer.php'); ?>