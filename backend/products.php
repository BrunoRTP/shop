<?php 
    $root_dir = $_SERVER['DOCUMENT_ROOT'] . '/student025/shop/backend/';
    include($root_dir . 'auth_functions.php');
    require_login();
    include($root_dir . 'header.php'); 
    include($root_dir . 'db_connection.php'); 
?>

<?php
    if($_SESSION['type_client'] == 'admin'){
        echo '<button>';
        echo '<a href="/student025/shop/backend/forms/form_products_insert.php" class="social-icon">Insertar Nuevo Producto</a>';
        echo '</button>';
    }
?>

<div class="search-container">
    <input 
        type="text" 
        id="search-input" 
        placeholder="Buscar productos"
    >
    <div id="search-count"></div>
</div>

<hr>

<div id="products-container">
<?php
    $sql = "SELECT * FROM 025_products";
    $result = mysqli_query($conn, $sql); 
    while ($row = mysqli_fetch_assoc($result)) {
        $id = $row['id'];
        $name = htmlspecialchars($row['name']);
        $description = htmlspecialchars($row['description']);
        
        // Verificar si tiene imagen
        $image_src = '/student025/shop/assets/img/ph.jpg'; // Placeholder por defecto
        if(!empty($row['image']) && $row['image'] !== null) {
            // Convertir el BLOB a base64 para mostrarlo
            $image_data = base64_encode($row['image']);
            $image_src = 'data:image/jpeg;base64,' . $image_data;
        }
        ?>
        
        <div class="producto-wrapper">
            <div class="producto-item">
                <img src="<?= $image_src ?>" class="mueble-placeholder" alt="<?= $name ?>">
                
                <div class="info-container">
                    <div class="producto-info">
                        ID: <?= $id ?>, Nombre: <?= $name ?>, Descripción: <?= $description ?>
                    </div>
                    
                    <?php if($_SESSION['type_client'] == 'admin'): ?>
                    <div class="producto-acciones">
                        <button>
                            <a href="/student025/shop/backend/forms/form_products_update_call.php?id=<?= $id ?>" class="social-icon">Update</a>
                        </button>
                        <button>
                            <a href="/student025/shop/backend/forms/form_products_delete_call.php?id=<?= $id ?>" class="social-icon">Delete</a>
                        </button>
                    </div>
                    <?php endif; ?>
                    
                    <button onclick="location.href='/student025/shop/backend/db/db_cart_insert.php?id=<?= $id ?>'" type="button">
                        <a href="/student025/shop/backend/db/db_cart_insert.php?id=<?= $id ?>" class="social-icon">Add to cart</a>
                    </button>
                </div>
            </div>
            <hr><br>
        </div>
        
        <?php
    }
    mysqli_close($conn);
?>
</div>

<?php
    if($_SESSION['type_client'] == 'admin'){
        echo '<button>';
        echo '<a href="/student025/shop/backend/forms/form_products_insert.php" class="social-icon">Insertar Nuevo Producto</a>';
        echo '</button>';
    }
?>

<script src="/student025/shop/js/search_products.js"></script>

<?php include($root_dir . 'footer.php'); ?>