<?php
    $root_dir = $_SERVER['DOCUMENT_ROOT']  . '/student025/shop/backend/';
    include($root_dir . 'header.php'); 
?>
<div class="container" style="padding: 20px;">
    <h2>Confirmacion</h2>
    
    <form action="/student025/shop/backend/db/bd_customers_select.php" method="POST">
        
        <label for="name">Estas seguro?</label><br><br>        
        <input type="hidden" name="user_id" value="<?= $user_id ?? '' ?>">
        <button type="submit" name="select_user">Aceptar</button>
        
        <a href="/student025/shop/backend/customers.php" style="margin-left: 10px;">Cancelar</a>
    </form>
</div>

<?php include($root_dir . 'footer.php'); ?>