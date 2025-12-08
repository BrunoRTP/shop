<?php
    session_start();
    $root_dir = $_SERVER['DOCUMENT_ROOT']  . '/student025/shop/backend/';
    include($root_dir . 'auth_functions.php');
    require_login();
    include($root_dir . 'header.php'); 
    include($root_dir . 'db_connection.php'); 
?>

<?php
    // Obtener product_id de la URL
    $product_id = isset($_GET['product_id']) ? $_GET['product_id'] : '';
    $customer_id = $_SESSION['user_id'];
    
    if(empty($product_id)){
        echo "Error: No se especificó el producto.";
        echo "<br><a href='/student025/shop/backend/orders.php'>Volver a pedidos</a>";
        exit;
    }
    
    // Obtener información del producto
    $sql = "SELECT name FROM 025_products WHERE id = $product_id";
    $result = mysqli_query($conn, $sql);
    $product = mysqli_fetch_assoc($result);
    
    if(!$product){
        echo "Error: Producto no encontrado.";
        echo "<br><a href='/student025/shop/backend/orders.php'>Volver a pedidos</a>";
        exit;
    }
    
    // Verificar si ya existe una review de este usuario para este producto
    $sql_check = "SELECT id FROM 025_reviews 
                  WHERE customer_id = $customer_id AND product_id = $product_id";
    $result_check = mysqli_query($conn, $sql_check);
    
    if(mysqli_num_rows($result_check) > 0){
        echo "<div style='color: orange; padding: 20px; background: #fff4e6; margin: 20px; border-radius: 5px;'>";
        echo "<h3>Ya has dejado una review para este producto</h3>";
        echo "<p>Solo puedes dejar una reseña por producto.</p>";
        echo "<a href='/student025/shop/backend/orders.php'>Volver a pedidos</a>";
        echo "</div>";
        mysqli_close($conn);
        include($root_dir . 'footer.php');
        exit;
    }
    
    mysqli_close($conn);
?>

<div class="container" style="padding: 20px;">
    <h2>Añadir Review</h2>
    
    <p><strong>Producto:</strong> <?= htmlspecialchars($product['name']) ?></p>
    <p><strong>Usuario:</strong> <?= htmlspecialchars($_SESSION['username']) ?></p>
    
    <form action="/student025/shop/backend/db/bd_review_insert.php" method="POST">
        
        <input type="hidden" name="customer_id" value="<?= $customer_id ?>">
        <input type="hidden" name="product_id" value="<?= $product_id ?>">
        
        <label for="score">Puntuación:</label>
        <select name="score" required>
            <option value="">Selecciona una puntuación</option>
            <option value="1">1 - Muy malo</option>
            <option value="2">2 - Malo</option>
            <option value="3">3 - Regular</option>
            <option value="4">4 - Bueno</option>
            <option value="5">5 - Excelente</option>
        </select><br><br>

        <label for="review">Reseña:</label>
        <textarea name="review" required maxlength="500" rows="6" 
                  style="width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #dbb69f; border-radius: 5px;"
                  placeholder="Cuéntanos tu experiencia con este producto..."></textarea>
        <small style="color: #666;">Máximo 500 caracteres</small><br><br>

        <button type="submit" name="insert_review" style="padding: 10px 20px; margin-right: 10px;">Enviar Review</button>
        
        <a href="/student025/shop/backend/orders.php" style="margin-left: 10px;">Cancelar</a>
    </form>
</div>

<?php include($root_dir . 'footer.php'); ?>