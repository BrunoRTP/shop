<?php 
    $root_dir = $_SERVER['DOCUMENT_ROOT'] . '/student025/shop/backend/';
    include($root_dir . 'auth_functions.php');
    require_login();
    include($root_dir . 'header.php'); 
    include($root_dir . 'db_connection.php'); 
?>

<?php
    echo '<button>';
    echo '<a href="/student025/shop/backend/forms/form_order_insert.php" class="social-icon">Crear Pedido</a>';
    echo '</button>';
?>

<hr>

<?php
    $sql = "SELECT o.*, c.username, p.name as product_name 
            FROM 025_order o
            LEFT JOIN 025_customers c ON o.customer_id = c.id
            LEFT JOIN 025_products p ON o.product_id = p.id";
    
    if($_SESSION['type_client'] != 'admin'){
        $user_id = $_SESSION['user_id'];
        $sql .= " WHERE o.customer_id = $user_id";
    }
    
    $sql .= " ORDER BY o.order_date DESC, o.id DESC";
    
    $result = mysqli_query($conn, $sql);
    
    // Agrupar pedidos por fecha
    $orders_by_date = [];
    while ($row = mysqli_fetch_assoc($result)) {
        // Guardamos la fecha
        $order_date = date('Y-m-d H:i:s', strtotime($row['order_date']));
        
        if(!isset($orders_by_date[$order_date])){
            $orders_by_date[$order_date] = [];
        }
        
        $orders_by_date[$order_date][] = $row;
    }
    
    // Mostrar pedidos agrupados
    if(empty($orders_by_date)){
        echo '<p>No hay pedidos registrados.</p>';
    } else {
        foreach($orders_by_date as $date => $orders){
            $total_group = 0;
            
            echo '<div class="order-group">';
            echo '<div class="order-group-header">';
            echo '<span>Pedido realizado el ' . date('d/m/Y', strtotime($date)) . ' a las ' . date('H:i', strtotime($date)) . '</span>';
            echo '</div>';
            
            foreach($orders as $row){
                $id = $row['id'];
                $total_group += $row['price'];
                
                echo '<div class="order-item">'; 
                
                echo '<div class="info-container">'; 
                
                echo '<div class="producto-info">';
                echo "<strong>ID:</strong> " . $id . " | ";
                
                if($_SESSION['type_client'] == 'admin'){
                    echo "<strong>Cliente:</strong> " . $row['username'] . " | ";
                }
                
                echo "<strong>Producto:</strong> " . $row['product_name'] . " | ";
                echo "<strong>Cantidad:</strong> " . $row['quantity'] . " | ";
                echo "<strong>Precio:</strong> €" . number_format($row['price'], 2);
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
                    echo '  <a href="/student025/shop/backend/forms/form_review_insert.php?product_id=' . $row['product_id'] . '" class="social-icon">Añadir Review</a>';
                    echo '</button>';
                    echo '<button>';
                    echo '  <a href="/student025/shop/backend/forms/form_order_delete_call.php?id=' . $id . '" class="social-icon">Cancelar Pedido</a>';
                    echo '</button>';
                    echo '</div>';
                }
                
                echo '</div>';
                
                echo '</div>';
            }
            
            echo '<div class="order-group-footer">';
            echo 'Total del pedido: €' . number_format($total_group, 2);
            echo '</div>';
            
            echo '</div>';
        }
    }
    
    mysqli_close($conn);
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