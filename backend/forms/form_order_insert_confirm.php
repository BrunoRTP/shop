<?php
    session_start();
    $root_dir = $_SERVER['DOCUMENT_ROOT']  . '/student025/shop/backend/';
    include($root_dir . 'auth_functions.php');
    require_login();
    include($root_dir . 'header.php'); 
    include($root_dir . 'db_connection.php'); 
?>
<?php
    $customer_id = $_POST['customer_id'];
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    
    $sql = "SELECT name, price FROM 025_products WHERE id = $product_id";
    $result = mysqli_query($conn, $sql);
    $product = mysqli_fetch_assoc($result);
    
    $total_price = $product['price'] * $quantity;
    
    mysqli_close($conn);
?>

<div class="container" style="padding: 20px;">
    <h2>Confirmar Pedido - Paso 2</h2>
    
    <p><strong>Usuario:</strong> <?= $_SESSION['username'] ?></p>
    <p><strong>Producto:</strong> <?= $product['name'] ?></p>
    <p><strong>Precio Unitario:</strong> €<?= $product['price'] ?></p>
    <p><strong>Cantidad:</strong> <?= $quantity ?></p>
    <p><strong>Precio Total:</strong> €<?= number_format($total_price, 2) ?></p>
    
    <form action="/student025/shop/backend/db/bd_order_insert.php" method="POST">
        <input type="hidden" name="customer_id" value="<?= $customer_id ?>">
        <input type="hidden" name="product_id" value="<?= $product_id ?>">
        <input type="hidden" name="quantity" value="<?= $quantity ?>">
        
        <button type="submit" name="insert_order">Confirmar Pedido</button>
        <a href="/student025/shop/backend/forms/form_order_insert.php" style="margin-left: 10px;">Volver</a>
    </form>
</div>

<?php include($root_dir . 'footer.php'); ?>