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
        
        <!-- Mostrar imagen actual si existe -->
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
        <small style="display: block; color: #666; margin-top: 5px;">
            <?= $current_image ? 'Deja vacío si no quieres cambiar la imagen. ' : '' ?>
            Formatos: JPG, JPEG, PNG. Máximo: 2MB
        </small>
        <br><br>
        
        <!-- Preview de la nueva imagen -->
        <div id="image-preview" style="display: none; margin-bottom: 20px;">
            <label>Nueva imagen (vista previa):</label><br>
            <img id="preview-img" src="" alt="Preview" style="max-width: 300px; max-height: 300px; border: 2px solid #4CAF50; border-radius: 8px;">
        </div>

        <input type="hidden" name="id" value="<?= $product['id'] ?? '' ?>">

        <button type="submit" name="update_product">Guardar Cambios</button>
        
        <a href="/student025/shop/backend/products.php" style="margin-left: 10px;">Cancelar</a>
    </form>
</div>

<script>
// Preview de imagen
document.getElementById('product_image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        // Validar tamaño (2MB)
        if (file.size > 2 * 1024 * 1024) {
            alert('La imagen es demasiado grande. Tamaño máximo: 2MB');
            this.value = '';
            document.getElementById('image-preview').style.display = 'none';
            return;
        }
        
        // Validar tipo
        if (!file.type.match('image/jpeg') && !file.type.match('image/png') && !file.type.match('image/jpg')) {
            alert('Solo se permiten archivos JPG, JPEG o PNG');
            this.value = '';
            document.getElementById('image-preview').style.display = 'none';
            return;
        }
        
        // Mostrar preview
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview-img').src = e.target.result;
            document.getElementById('image-preview').style.display = 'block';
        }
        reader.readAsDataURL(file);
    } else {
        document.getElementById('image-preview').style.display = 'none';
    }
});
</script>

<?php include($root_dir . 'footer.php'); ?>