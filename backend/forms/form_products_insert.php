<?php
    $root_dir = $_SERVER['DOCUMENT_ROOT']  . '/student025/shop/backend/';
    include($root_dir . 'auth_functions.php');
    require_login();
    include($root_dir . 'header.php'); 
    include($root_dir . 'db_connection.php'); 
?>
<?php    
    $sql = "SELECT * FROM 025_categories";
    $result_categories = mysqli_query($conn, $sql);
    $categories = mysqli_fetch_all($result_categories, MYSQLI_ASSOC);
    mysqli_close($conn)
?>
<div class="container" style="padding: 20px;">
    <h2>Añadir Nuevo Producto</h2>
    
    <form action="/student025/shop/backend/db/bd_products_insert.php" method="POST">
        
        <label for="name">Nombre del Producto:</label>
        <input type="text" name="name" pattern="[a-zA-Z0-9]+" title="Solo letras y números están permitidos." required><br><br>

        <label for="description">Descripción:</label>
        <textarea name="description" pattern="[a-zA-Z0-9]+" required></textarea><br><br>

        <label for="quantity">Cantidad en Stock:</label>
        <input type="number" name="quantity_available" required min="0"><br><br>

        <label for="price">Precio:</label>
        <input type="number" name="price" required min="0"><br><br>
        <label for="category_id">Categoría:</label>
        <select name="category_id" required>
            <?php
                // Fetch categories
                foreach($categories as $category){
                    $selected = $product['id'] == $category['id'] ? 'selected' : '';
                    echo "<option value=\"{$category['id']}\" {$selected}>{$category['name']}</option>";
                }
            ?>
        </select>
        <br><br>
        

        <button type="submit" name="insert_product">Guardar Producto</button>
        
        <a href= "/student025/shop/backend/products.php" style="margin-left: 10px;">Cancelar</a>
    </form>
</div>

<?php include($root_dir . 'footer.php'); ?>