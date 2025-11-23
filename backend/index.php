
    
    <?php 
        $root_dir = $_SERVER['DOCUMENT_ROOT']  . '/student025/shop/backend/';
        include($root_dir . 'auth_functions.php');
        require_login();
        include($root_dir . 'header.php'); 
    ?>
    <h1>Bienvenido</h1>
    <?php include($root_dir . 'footer.php') ?>