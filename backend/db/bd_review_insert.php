<?php
session_start();
$root_dir = $_SERVER['DOCUMENT_ROOT'] . '/student025/shop/backend/';
include($root_dir . 'auth_functions.php');
require_login();
include($root_dir. 'header.php'); 
include($root_dir . 'db_connection.php'); 
?>

<?php
    if(isset($_POST['insert_review'])){
        $customer_id = $_POST['customer_id'];
        $product_id = $_POST['product_id'];
        $score = $_POST['score'];
        $review = mysqli_real_escape_string($conn, $_POST['review']);
        
        // Validaciones
        $errors = [];
        
        // Verificar que el customer_id coincida con el usuario de la sesión
        if($customer_id != $_SESSION['user_id']){
            $errors[] = "Error: No puedes crear reviews para otros usuarios.";
        }
        
        // Validar score (1-5)
        if(!is_numeric($score) || $score < 1 || $score > 5){
            $errors[] = "La puntuación debe ser entre 1 y 5.";
        }
        
        // Validar review (no vacío, máximo 500 caracteres)
        if(empty(trim($review))){
            $errors[] = "La reseña no puede estar vacía.";
        }
        if(strlen($review) > 500){
            $errors[] = "La reseña no puede tener más de 500 caracteres.";
        }
        
        // Verificar que no exista ya una review de este usuario para este producto
        $sql_check = "SELECT id FROM 025_reviews 
                      WHERE customer_id = $customer_id AND product_id = $product_id";
        $result_check = mysqli_query($conn, $sql_check);
        
        if(mysqli_num_rows($result_check) > 0){
            $errors[] = "Ya has dejado una review para este producto.";
        }
        
        // Si hay errores, mostrarlos
        if(!empty($errors)){
            echo "<div style='color: red; padding: 10px; background: #ffe6e6; margin: 10px;'>";
            echo "<strong>Errores encontrados:</strong><ul>";
            foreach($errors as $error){
                echo "<li>" . htmlspecialchars($error) . "</li>";
            }
            echo "</ul>";
            echo "<a href='/student025/shop/backend/orders.php'>Volver a pedidos</a>";
            echo "</div>";
        } else {
            // Establecer zona horaria de España
            date_default_timezone_set('Europe/Madrid');
            $created_at = date('Y-m-d H:i:s');
            
            $sql = "INSERT INTO 025_reviews (customer_id, product_id, score, review, created_at) 
                    VALUES ($customer_id, $product_id, $score, '$review', '$created_at')";

            $result = mysqli_query($conn, $sql);
            
            if($result){
                if (mysqli_affected_rows($conn) > 0) {
                    echo "<div style='color: green; padding: 10px; background: #e6ffe6; margin: 10px;'>";
                    echo "¡Review añadida exitosamente!";
                    echo "<br><br><a href='/student025/shop/backend/orders.php'>Volver a pedidos</a>";
                    echo " | <a href='/student025/shop/backend/products.php'>Ver productos</a>";
                    echo "</div>";
                } else {
                    echo "<div style='color: orange; padding: 10px; background: #fff4e6; margin: 10px;'>";
                    echo "No se añadió ninguna review.";
                    echo "<br><br><a href='/student025/shop/backend/orders.php'>Volver a pedidos</a>";
                    echo "</div>";
                }
            } else {
                echo "<div style='color: red; padding: 10px; background: #ffe6e6; margin: 10px;'>";
                echo "Error al insertar: " . htmlspecialchars(mysqli_error($conn));
                echo "<br><br><a href='/student025/shop/backend/orders.php'>Volver a pedidos</a>";
                echo "</div>";
            }
        }
        
        unset($_POST["insert_review"]);
    }
    mysqli_close($conn);
?>

<?php include($root_dir . 'footer.php'); ?>