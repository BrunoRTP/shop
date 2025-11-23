<?php
$root_dir = $_SERVER['DOCUMENT_ROOT'] . '/student025/shop/backend/';
include($root_dir. 'header.php'); 
include($root_dir . 'db_connection.php'); 
?>

<?php
    if(isset($_POST['insert_product'])){
        $errors = [];
        
        // Validar nombre - solo letras, números, espacios y algunos caracteres permitidos
        $name = trim($_POST['name']);
        if(!preg_match('/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s\-\.]+$/', $name)){
            $errors[] = "El nombre del producto solo puede contener letras, números, espacios, guiones y puntos.";
        }
        
        // Validar descripción - permitir más caracteres pero sin código
        $description = trim($_POST['description']);
        if(preg_match('/<script|javascript:|on\w+\s*=/i', $description)){
            $errors[] = "La descripción contiene contenido no permitido.";
        }
        
        // Validar cantidad - solo números enteros positivos
        $quantity_available = $_POST['quantity_available'] ?? 0;
        if(!is_numeric($quantity_available) || $quantity_available < 0 || floor($quantity_available) != $quantity_available){
            $errors[] = "La cantidad debe ser un número entero positivo.";
        }
        
        // Validar precio - solo números positivos
        $price = $_POST['price'] ?? 0;
        if(!is_numeric($price) || $price < 0){
            $errors[] = "El precio debe ser un número positivo.";
        }
        
        // Validar categoría - solo números enteros
        $category_id = $_POST['category_id'] ?? 1;
        if(!is_numeric($category_id) || floor($category_id) != $category_id){
            $errors[] = "La categoría no es válida.";
        }
        
        // Si hay errores, mostrarlos
        if(!empty($errors)){
            echo "<strong>Errores encontrados:</strong><ul>";
            foreach($errors as $error){
                echo "<li>" . htmlspecialchars($error) . "</li>";
            }
            echo "</ul></div>";
        } else {
            // Usar prepared statements para prevenir inyección SQL
            $sql = "INSERT INTO 025_products (name, description, stock, price, category_id) 
                    VALUES (?, ?, ?, ?, ?)";
            
            $stmt = mysqli_prepare($conn, $sql);
            
            if($stmt){
                mysqli_stmt_bind_param($stmt, "ssidi", $name, $description, $quantity_available, $price, $category_id);
                
                if(mysqli_stmt_execute($stmt)){
                    if (mysqli_stmt_affected_rows($stmt) > 0) {
                        echo "<div style='color: green; padding: 10px; background: #e6ffe6; margin: 10px;'>";
                        echo "¡Producto añadido exitosamente!";
                        echo "</div>";
                    } else {
                        echo "<div style='color: orange; padding: 10px; background: #fff4e6; margin: 10px;'>";
                        echo "No se añadió ninguna fila.";
                        echo "</div>";
                    }
                } else {
                    echo "<div style='color: red; padding: 10px; background: #ffe6e6; margin: 10px;'>";
                    echo "Error al insertar: " . htmlspecialchars(mysqli_error($conn));
                    echo "</div>";
                }
                
                mysqli_stmt_close($stmt);
            } else {
                echo "<div style='color: red; padding: 10px; background: #ffe6e6; margin: 10px;'>";
                echo "Error en la preparación de la consulta: " . htmlspecialchars(mysqli_error($conn));
                echo "</div>";
            }
        }
        
        unset($_POST["insert_product"]);
    }
?>

<?php
    $root_dir = $_SERVER['DOCUMENT_ROOT']  . '/student025/shop/backend/';
?>
<?php    
    $sql = "SELECT * FROM 025_categories";
    $result_categories = mysqli_query($conn, $sql);
    $categories = mysqli_fetch_all($result_categories, MYSQLI_ASSOC);
    mysqli_close($conn);
?>
<div class="container" style="padding: 20px;">
    <h2>Añadir Nuevo Producto</h2>
    
    <form action="/student025/shop/backend/db/bd_products_insert.php" method="POST">
        
        <label for="name">Nombre del Producto:</label>
        <input type="text" name="name" required pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s\.\-]+" 
               title="Solo letras, números, espacios, puntos y guiones"><br><br>

        <label for="description">Descripción:</label>
        <textarea name="description" required maxlength="1000"></textarea><br><br>

        <label for="quantity_available">Cantidad en Stock:</label>
        <input type="number" name="quantity_available" required min="0" step="1"><br><br>

        <label for="price">Precio:</label>
        <input type="number" name="price" required min="0" step="0.01"><br><br>
        
        <label for="category_id">Categoría:</label>
        <select name="category_id" required>
            <?php
                foreach($categories as $category){
                    echo "<option value=\"" . htmlspecialchars($category['id']) . "\">" 
                         . htmlspecialchars($category['name']) . "</option>";
                }
            ?>
        </select>
        <br><br>
        
        <button type="submit" name="insert_product">Guardar Producto</button>
        
        <a href="/student025/shop/backend/products.php" style="margin-left: 10px;">Cancelar</a>
    </form>
</div>

<?php include($root_dir . 'footer.php'); ?>