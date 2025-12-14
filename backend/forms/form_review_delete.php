<?php
    $root_dir = $_SERVER['DOCUMENT_ROOT']  . '/student025/shop/backend/';
    include($root_dir . 'auth_functions.php');
    require_login();
    include($root_dir . 'header.php'); 
?>

<?php
    // Verificar que sea admin
    if($_SESSION['type_client'] != 'admin'){
        echo "<div class='message-error'>";
        echo "<h3>Acceso Denegado</h3>";
        echo "<p>Solo los administradores pueden eliminar reviews.</p>";
        echo "<a href='/student025/shop/backend/reviews.php'>Volver</a>";
        echo "</div>";
        include($root_dir . 'footer.php');
        exit;
    }
    
    $id = $_POST['id'];
?>

<div class="container" style="padding: 20px;">
    <h2>Confirmación</h2>
    
    <form action="/student025/shop/backend/db/bd_review_delete.php" method="POST">
        
        <label for="id">¿Estás seguro de eliminar esta review?</label><br><br>        
        <input type="hidden" name="id" value="<?= $id ?? '' ?>">
        <button type="submit" name="delete_review">Aceptar</button>
        
        <a href="/student025/shop/backend/reviews.php" style="margin-left: 10px;">Cancelar</a>
    </form>
</div>

<?php include($root_dir . 'footer.php'); ?>