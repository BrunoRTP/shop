<?php
    $root_dir = $_SERVER['DOCUMENT_ROOT']  . '/student025/shop/backend/';
    include($root_dir . 'auth_functions.php');
    require_login();
    include($root_dir . 'header.php'); 
    include($root_dir . 'db_connection.php'); 
?>
<?php
    
    $id = $_POST['id'];
    $customer_id = $_POST['customer_id'];
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    
    // Si no es admin, verificar que sea su propio pedido
    if($_SESSION['type_client'] != 'admin' && $customer_id != $_SESSION['user_id']){
        echo "No tienes permiso para editar este pedido.";
        exit;
    }
    
    $sql = "SELECT name, price FROM 025_products WHERE id = $product_id";
    $result = mysqli_query($conn, $sql);
    $product = mysqli_fetch_assoc($result);
    
    $total_price = $product['price'] * $quantity;
    
    mysqli_close($conn);
?>

<div class="container" style="padding: 20px;">
    <h2>Confirmar Actualización - Paso 2</h2>
    
    <p><strong>Usuario:</strong> <?= $_SESSION['username'] ?></p>
    <p><strong>Producto:</strong> <?= $product['name'] ?></p>
    <p><strong>Precio Unitario:</strong> €<?= $product['price'] ?></p>
    <p><strong>Cantidad:</strong> <?= $quantity ?></p>
    <p><strong>Precio Total:</strong> €<?= number_format($total_price, 2) ?></p>
    
    <form action="/student025/shop/backend/db/bd_order_update.php" method="POST">
        <input type="hidden" name="id" value="<?= $id ?>">
        <input type="hidden" name="customer_id" value="<?= $customer_id ?>">
        <input type="hidden" name="product_id" value="<?= $product_id ?>">
        <input type="hidden" name="quantity" value="<?= $quantity ?>">
        
        <button type="submit" name="update_order">Confirmar Actualización</button>
        <a href="/student025/shop/backend/orders.php" style="margin-left: 10px;">Cancelar</a>
    </form>
</div>

<?php include($root_dir . 'footer.php'); ?>