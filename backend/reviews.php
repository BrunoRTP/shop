<?php 
    $root_dir = $_SERVER['DOCUMENT_ROOT'] . '/student025/shop/backend/';
    include($root_dir . 'auth_functions.php');
    require_login();
    include($root_dir . 'header.php'); 
    include($root_dir . 'db_connection.php'); 
?>

<?php
    // Verificar que sea admin
    if($_SESSION['type_client'] != 'admin'){
        echo "<div class='message-error'>";
        echo "<h3>Acceso Denegado</h3>";
        echo "<p>Solo los administradores pueden gestionar las reviews.</p>";
        echo "<a href='/student025/shop/backend/index.php'>Volver al inicio</a>";
        echo "</div>";
        include($root_dir . 'footer.php');
        exit;
    }
?>

<div class="search-container">
    <input 
        type="text" 
        id="search-input" 
        placeholder="Buscar reviews por usuario, producto o contenido..."
    >
    <div id="search-count"></div>
</div>

<hr>

<div id="reviews-container">
<?php
    // Obtener todas las reviews con información de usuario y producto
    $sql = "SELECT r.*, 
                   c.username, 
                   p.name as product_name 
            FROM 025_reviews r
            LEFT JOIN 025_customers c ON r.customer_id = c.id
            LEFT JOIN 025_products p ON r.product_id = p.id
            ORDER BY r.created_at DESC";
    
    $result = mysqli_query($conn, $sql);
    
    if(mysqli_num_rows($result) == 0){
        echo '<p class="no-products-message">No hay reviews registradas.</p>';
    } else {
        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row['id'];
            $username = htmlspecialchars($row['username']);
            $product_name = htmlspecialchars($row['product_name']);
            $score = $row['score'];
            $review = htmlspecialchars($row['review']);
            $created_at = date('d/m/Y H:i', strtotime($row['created_at']));
            
            // Generar estrellas
            $stars = str_repeat('⭐', $score);
            
            echo '<div class="producto-wrapper review-wrapper">'; 
            
            echo '<div class="producto-item review-item">'; 
            
            // Icono de review
            echo '<div class="review-icon">';
            echo '<span class="review-icon-emoji">💬</span>';
            echo '</div>';
            
            echo '<div class="info-container">'; 
            
            echo '<div class="producto-info review-info">';
            echo '<div class="review-header">';
            echo '<strong class="review-username">' . $username . '</strong>';
            echo '<span class="review-date">' . $created_at . '</span>';
            echo '</div>';
            
            echo '<div class="review-product">';
            echo '<strong>Producto:</strong> ' . $product_name;
            echo '</div>';
            
            echo '<div class="review-score">';
            echo '<strong>Puntuación:</strong> ' . $stars . ' (' . $score . '/5)';
            echo '</div>';
            
            echo '<div class="review-content">';
            echo '<strong>Comentario:</strong><br>';
            echo '<p class="review-text">' . $review . '</p>';
            echo '</div>';
            
            echo '</div>'; // fin producto-info
            
            echo '<div class="producto-acciones">';
            echo '<button class="btn-delete-review">';
            echo '  <a href="/student025/shop/backend/forms/form_review_delete_call.php?id=' . $id . '" class="social-icon"><span>🗑️</span> Eliminar Review</a>';
            echo '</button>';
            echo '</div>';
            
            echo '</div>'; // fin info-container
            
            echo '</div>'; // fin producto-item
            
            echo '</div>'; // fin producto-wrapper
        }
    }
    
    mysqli_close($conn);
?>
</div>

<?php include($root_dir . 'footer.php'); ?>