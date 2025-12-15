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

<div id="customers-container">
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
        $username = htmlspecialchars($row['username']);
        $type = htmlspecialchars($row['type_client']);
        ?>
        
        <div class="producto-wrapper">
            <div class="producto-item">
                <div class="customer-icon">
                    <span class="customer-icon-emoji">👤</span>
                </div>
                
                <div class="info-container">
                    <div class="producto-info">
                        <strong>ID:</strong> <?= $id ?> | 
                        <strong>Usuario:</strong> <?= $username ?> | 
                        <strong>Tipo:</strong> <?= $type ?>
                    </div>
                    
                    <div class="producto-acciones">
                        <button>
                            <a href="/student025/shop/backend/forms/form_customers_update_call.php?id=<?= $id ?>" class="social-icon">Update</a>
                        </button>
                        
                        <?php if($_SESSION['type_client'] == 'admin'): ?>
                            <button>
                                <a href="/student025/shop/backend/forms/form_customers_delete_call.php?id=<?= $id ?>" class="social-icon">Delete</a>
                            </button>
                        <?php else: ?>
                            <button>
                                <a href="/student025/shop/backend/forms/form_customers_delete_call.php?id=<?= $id ?>" class="social-icon">Eliminar mi cuenta</a>
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <?php
    }
    mysqli_close($conn);
?>
</div>

<?php
    if($_SESSION['type_client'] == 'admin'){
        echo '<button>';
        echo '<a href="/student025/shop/backend/forms/form_customers_insert.php" class="social-icon">Insertar Nuevo Cliente</a>';
        echo '</button>';
    }
?>

<?php include($root_dir . 'footer.php'); ?>