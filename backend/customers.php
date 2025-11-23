<?php 
    session_start();
    $root_dir = $_SERVER['DOCUMENT_ROOT'] . '/student025/shop/backend/';
    include($root_dir . 'header.php'); 
    include($root_dir . 'db_connection.php'); 
?>
<?php
    if(!isset($_SESSION['user_id'])){
        header("Location: /student025/shop/backend/form_login.php");     
        exit; 
    }
?>

<?php
    if($_SESSION['type_client'] == 'admin'){
        echo '<button>';
        echo '<a href="/student025/shop/backend/forms/form_customers_insert.php" class="social-icon">Insertar Nuevo Cliente</a>';
        echo '</button>';
    }
?>

<hr>
<?php
    if($_SESSION['type_client'] == 'admin'){
        $sql = "SELECT * FROM 025_customers";
    } else {
        $user_id = $_SESSION['user_id'];
        $sql = "SELECT * FROM 025_customers WHERE id = $user_id";
    }
    
    $result = mysqli_query($conn, $sql); 
    
    while ($row = mysqli_fetch_assoc($result)) {
        $id = $row['id'];
        
        echo '<div class="producto-item">'; 
        
        echo '<div class="info-container">'; 
        
        echo '<div class="producto-info">';
        echo "ID: " . $id . ", Usuario: " . $row['username'] . ", Tipo: " . $row['type_client'] . " ";
        echo '</div>';
        
        echo '<div class="producto-acciones">';
        echo '<button>';
        echo '  <a href="/student025/shop/backend/forms/form_customers_update_call.php?id=' . $id . '" class="social-icon">Update</a>';
        echo '</button>';
        
        if($_SESSION['type_client'] == 'admin'){
            echo '<button>';
            echo '  <a href="/student025/shop/backend/forms/form_customers_delete_call.php?id=' . $id . '" class="social-icon">Delete</a>';
            echo '</button>';
        } else {
            echo '<button>';
            echo '  <a href="/student025/shop/backend/forms/form_customers_delete_call.php?id=' . $id . '" class="social-icon">Eliminar mi cuenta</a>';
            echo '</button>';
        }
        echo '</div>';
        
        echo '</div>';
        
        echo '</div>';
        
        echo "<hr><br>"; 
    }
?>

<?php
    if($_SESSION['type_client'] == 'admin'){
        echo '<button>';
        echo '<a href="/student025/shop/backend/forms/form_customers_insert.php" class="social-icon">Insertar Nuevo Cliente</a>';
        echo '</button>';
    }
?>

<?php include($root_dir . 'footer.php'); ?>