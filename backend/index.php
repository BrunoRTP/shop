
    
    <?php 
        $root_dir = $_SERVER['DOCUMENT_ROOT']  . '/student025/shop/backend/';
        include($root_dir . 'auth_functions.php');
        require_login();
        include($root_dir . 'header.php'); 
    ?>
    <h1>Bienvenido</h1>
    <button>
        <a href="/student025/shop/backend/api/get_Bruno_products.php?api_key=3333" class="social-icon">Ver productos</a>
    </button>
    <button>
        <a href="/student025/shop/backend/api/importar_productos.php" class="social-icon">Pillar productos del compañero</a>
    </button>
    <?php include($root_dir . 'footer.php') ?>