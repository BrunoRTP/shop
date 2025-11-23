<?php
    $root_dir = $_SERVER['DOCUMENT_ROOT']  . '/student025/shop/backend/';
    include($root_dir . 'auth_functions.php');
    require_login();
    include($root_dir . 'header.php'); 
    include($root_dir . 'db_connection.php'); 
?>
<?php 
    // capturar informacion de la bd
    $id = $_POST['id'];
    
    // Get order
    $sql = "SELECT * FROM 025_order WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    $order = mysqli_fetch_assoc($result);
    
    // Get customers
    $sql = "SELECT * FROM 025_customers";
    $result_customers = mysqli_query($conn, $sql);
    $customers = mysqli_fetch_all($result_customers, MYSQLI_ASSOC);
    
    // Get products
    $sql = "SELECT * FROM 025_products";
    $result_products = mysqli_query($conn, $sql);
    $products = mysqli_fetch_all($result_products, MYSQLI_ASSOC);
    
    mysqli_close($conn);
?>
<div class="container" style="padding: 20px;">
    <h2>Actualizar Pedido - Paso 1</h2>
    
    <p><strong>Usuario:</strong> <?= $_SESSION['username'] ?></p>
    
    <form action="/student025/shop/backend/forms/form_order_update_confirm.php" method="POST">
        
        <input type="hidden" name="id" value="<?= $order['id'] ?? '' ?>">
        <input type="hidden" name="customer_id" value="<?= $order['customer_id'] ?? '' ?>">

        <label for="product_id">Producto:</label>
        <select name="product_id" required>
            <?php
                foreach($products as $product){
                    $selected = $order['product_id'] == $product['id'] ? 'selected' : '';
                    echo "<option value=\"{$product['id']}\" {$selected}>{$product['name']} - €{$product['price']}</option>";
                }
            ?>
        </select><br><br>

        <label for="quantity">Cantidad:</label>
        <input type="number" name="quantity" pattern="[0-9]+" title="Solo números están permitidos." required min="1" value="<?= $order['quantity'] ?? '' ?>"><br><br>

        <button type="submit" name="next_step">Siguiente</button>
        
        <a href="/student025/shop/backend/orders.php" style="margin-left: 10px;">Cancelar</a>
    </form>
</div>

<?php include($root_dir . 'footer.php'); ?>