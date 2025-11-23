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
        echo '<a href="/student025/shop/backend/forms/form_order_insert.php" class="social-icon">Insertar Nuevo Pedido</a>';
        echo '</button>';
    } else {
        echo '<button>';
        echo '<a href="/student025/shop/backend/forms/form_order_insert.php" class="social-icon">Crear Pedido</a>';
        echo '</button>';
    }
?>

<hr>
<?php
    if($_SESSION['type_client'] == 'admin'){
        $sql = "SELECT o.*, c.username, p.name as product_name 
                FROM 025_order o
                LEFT JOIN 025_customers c ON o.customer_id = c.id
                LEFT JOIN 025_products p ON o.product_id = p.id";
    } else {

        $user_id = $_SESSION['user_id'];
        $sql = "SELECT o.*, c.username, p.name as product_name 
                FROM 025_order o
                LEFT JOIN 025_customers c ON o.customer_id = c.id
                LEFT JOIN 025_products p ON o.product_id = p.id
                WHERE o.customer_id = $user_id";
    }
    
    $result = mysqli_query($conn, $sql); 
    
    while ($row = mysqli_fetch_assoc($result)) {
        $id = $row['id'];
        
        echo '<div class="producto-item">'; 
        
        echo '<div class="info-container">'; 
        
        echo '<div class="producto-info">';
        echo "ID: " . $id . ", Cliente: " . $row['username'] . ", Producto: " . $row['product_name'] . ", Cantidad: " . $row['quantity'] . ", Precio: €" . $row['price'] . " ";
        echo '</div>';
        
        if($_SESSION['type_client'] == 'admin'){
            echo '<div class="producto-acciones">';
            echo '<button>';
            echo '  <a href="/student025/shop/backend/forms/form_order_update_call.php?id=' . $id . '" class="social-icon">Update</a>';
            echo '</button>';
            echo '<button>';
            echo '  <a href="/student025/shop/backend/forms/form_order_delete_call.php?id=' . $id . '" class="social-icon">Delete</a>';
            echo '</button>';
            echo '</div>';
        } else {
            echo '<div class="producto-acciones">';
            echo '<button>';
            echo '  <a href="/student025/shop/backend/forms/form_order_delete_call.php?id=' . $id . '" class="social-icon">Cancelar Pedido</a>';
            echo '</button>';
            echo '</div>';
        }
        echo '</div>';
        
        echo '</div>';
        
        echo "<hr><br>"; 
    }
?>

<?php
    if($_SESSION['type_client'] == 'admin'){
        echo '<button>';
        echo '<a href="/student025/shop/backend/forms/form_order_insert.php" class="social-icon">Insertar Nuevo Pedido</a>';
        echo '</button>';
    } else {
        echo '<button>';
        echo '<a href="/student025/shop/backend/forms/form_order_insert.php" class="social-icon">Crear Pedido</a>';
        echo '</button>';
    }
?>

<?php include($root_dir . 'footer.php'); ?>