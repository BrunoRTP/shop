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
        
        echo '<div class="producto-wrapper">'; 
        
        echo '<div class="producto-item">'; 
        
        echo '<img src="/student025/shop/assets/img/ph.jpg" class="mueble-placeholder">';
        
        echo '<div class="info-container">'; 
        
        echo '<div class="producto-info">';
        echo "ID: " . $id . ", Nombre: " . $name . ", Descripcion: " . $description . " ";
        echo '</div>';
        if($_SESSION['type_client'] == 'admin'){
            echo '<div class="producto-acciones">';
            echo '<button>';
            echo '  <a href="/student025/shop/backend/forms/form_products_update_call.php?id=' . $id . '" class="social-icon">Update</a>';
            echo '</button>';
            echo '<button>';
            echo '  <a href="/student025/shop/backend/forms/form_products_delete_call.php?id=' . $id . '" class="social-icon">Delete</a>';
            echo '</button>';
            echo '</div>';
        }
        echo '<button onClick="location.href=\'/student025/shop/backend/db/db_cart_insert.php?id=' . $id . '\'" type="button">';
        echo '  <a href="/student025/shop/backend/db/db_cart_insert.php?id=' . $id . '" class="social-icon">Add to cart</a>';
        echo '</button>';
        echo '</div>';
        
        echo '</div>';
        
        echo '<hr><br>';
        
        echo '</div>'; 
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