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
    // Get products
    $sql = "SELECT * FROM 025_products WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    $product = mysqli_fetch_assoc($result);
    // Get categories
    $sql = "SELECT * FROM 025_categories";
    $result_categories = mysqli_query($conn, $sql);
    $categories = mysqli_fetch_all($result_categories, MYSQLI_ASSOC);
    
    // Preparar la imagen actual si existe
    $current_image = null;
    if(!empty($product['image'])) {
        $current_image = 'data:image/jpeg;base64,' . base64_encode($product['image']);
    }
    
    mysqli_close($conn)
?>
<div class="container" style="padding: 20px;">
    <h2>Actualizar Producto</h2>
    
    <form action="/student025/shop/backend/db/bd_products_update.php" method="POST" enctype="multipart/form-data">
        
        <label for="name">Nombre del Producto:</label>
        <input type="text" name="name" value="<?= htmlspecialchars($product['name'] ?? '') ?>" required><br><br>

        <label for="description">Descripción:</label>
        <textarea name="description"><?= htmlspecialchars($product['description'] ?? '') ?></textarea><br><br>

        <label for="quantity">Cantidad en Stock:</label>
        <input type="number" name="quantity_available" required min="0" value="<?= $product['stock'] ?? 0 ?>"><br><br>

        <label for="price">Precio:</label>
        <input type="number" name="price" required min="0" step="0.01" value="<?= $product['price'] ?? 0 ?>"><br><br>
        
        <label for="category_id">Categoría:</label>
        <select name="category_id" required>
            <?php
                foreach($categories as $category){
                    $selected = $product['category_id'] == $category['id'] ? 'selected' : '';
                    echo "<option value=\"{$category['id']}\" {$selected}>{$category['name']}</option>";
                }
            ?>
        </select>
        <br><br>
        
        <?php if($current_image): ?>
        <div style="margin-bottom: 20px;">
            <label>Imagen actual:</label><br>
            <img src="<?= $current_image ?>" alt="Imagen actual" style="max-width: 300px; max-height: 300px; border: 2px solid #dbb69f; border-radius: 8px;">
        </div>
        <?php endif; ?>
        
        <label for="product_image">
            <?= $current_image ? 'Cambiar imagen:' : 'Añadir imagen:' ?>
        </label>
        <input type="file" name="product_image" id="product_image" accept="image/jpeg,image/png,image/jpg">
        <br><br>

        <input type="hidden" name="id" value="<?= $product['id'] ?? '' ?>">

        <button type="submit" name="update_product">Guardar Cambios</button>
        
        <a href="/student025/shop/backend/products.php" style="margin-left: 10px;">Cancelar</a>
    </form>
</div>

<?php include($root_dir . 'footer.php'); ?>