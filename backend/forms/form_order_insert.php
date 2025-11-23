<?php
    session_start();
    $root_dir = $_SERVER['DOCUMENT_ROOT']  . '/student025/shop/backend/';
    include($root_dir . 'auth_functions.php');
    require_login();
    include($root_dir . 'header.php'); 
    include($root_dir . 'db_connection.php'); 
?>
<?php
    // Verificar que el usuario esté logueado
    if(!isset($_SESSION['user_id'])){
        header("Location: /student025/shop/backend/form_login.php");     
        exit; 
    }
?>
<?php    
    // Get products
    $sql = "SELECT * FROM 025_products";
    $result_products = mysqli_query($conn, $sql);
    $products = mysqli_fetch_all($result_products, MYSQLI_ASSOC);
    
    mysqli_close($conn);
?>
<div class="container" style="padding: 20px;">
    <h2>Añadir Nuevo Pedido - Paso 1</h2>
    
    <p><strong>Usuario:</strong> <?= $_SESSION['username'] ?? 'Desconocido' ?></p>
    
    <form action="/student025/shop/backend/forms/form_order_insert_confirm.php" method="POST">
        
        <input type="hidden" name="customer_id" value="<?= $_SESSION['user_id'] ?>">
        
        <label for="product_id">Producto:</label>
        <select name="product_id" required>
            <option value="">Selecciona un producto</option>
            <?php
                foreach($products as $product){
                    echo "<option value=\"{$product['id']}\">{$product['name']} - €{$product['price']}</option>";
                }
            ?>
        </select><br><br>

        <label for="quantity">Cantidad:</label>
        <input type="number" name="quantity" pattern="[0-9]+" title="Solo números están permitidos." required min="1" value="1"><br><br>

        <button type="submit" name="next_step">Siguiente</button>
        
        <a href="/student025/shop/backend/orders.php" style="margin-left: 10px;">Cancelar</a>
    </form>
</div>

<?php include($root_dir . 'footer.php'); ?>