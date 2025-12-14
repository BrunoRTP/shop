<?php
    $root_dir = $_SERVER['DOCUMENT_ROOT']  . '/student025/shop/backend/';
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
        echo "<p>Solo los administradores pueden eliminar reviews.</p>";
        echo "<a href='/student025/shop/backend/reviews.php'>Volver</a>";
        echo "</div>";
        mysqli_close($conn);
        include($root_dir . 'footer.php');
        exit;
    }
    
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    
    if($id <= 0){
        echo "<div class='message-error'>";
        echo "<h3>Error</h3>";
        echo "<p>ID de review no válido.</p>";
        echo "<a href='/student025/shop/backend/reviews.php'>Volver</a>";
        echo "</div>";
        mysqli_close($conn);
        include($root_dir . 'footer.php');
        exit;
    }
    
    // Obtener información de la review
    $sql = "SELECT r.*, c.username, p.name as product_name 
            FROM 025_reviews r
            LEFT JOIN 025_customers c ON r.customer_id = c.id
            LEFT JOIN 025_products p ON r.product_id = p.id
            WHERE r.id = $id";
    
    $result = mysqli_query($conn, $sql);
    $review = mysqli_fetch_assoc($result);
    
    if(!$review){
        echo "<div class='message-error'>";
        echo "<h3>Error</h3>";
        echo "<p>Review no encontrada.</p>";
        echo "<a href='/student025/shop/backend/reviews.php'>Volver</a>";
        echo "</div>";
        mysqli_close($conn);
        include($root_dir . 'footer.php');
        exit;
    }
    
    mysqli_close($conn);
?>

<div class="container review-delete-container">
    <h2 class="review-delete-title">Confirmar Eliminación de Review</h2>
    
    <div class="review-warning-box">
        <p>
            <strong>Atención:</strong> Esta acción no se puede deshacer. La review será eliminada permanentemente.
        </p>
    </div>
    
    <div class="review-info-box">
        <h3>Información de la Review:</h3>
        
        <div class="review-info-item">
            <strong>Usuario:</strong> 
            <span><?= htmlspecialchars($review['username']) ?></span>
        </div>
        
        <div class="review-info-item">
            <strong>Producto:</strong> 
            <span><?= htmlspecialchars($review['product_name']) ?></span>
        </div>
        
        <div class="review-info-item">
            <strong>Puntuación:</strong> 
            <span><?= str_repeat('⭐', $review['score']) ?> (<?= $review['score'] ?>/5)</span>
        </div>
        
        <div class="review-info-item">
            <strong>Fecha:</strong> 
            <span><?= date('d/m/Y H:i', strtotime($review['created_at'])) ?></span>
        </div>
        
        <div class="review-comment-box">
            <strong>Comentario:</strong>
            <p class="review-comment-text">
                <?= htmlspecialchars($review['review']) ?>
            </p>
        </div>
    </div>
    
    <form action="/student025/shop/backend/forms/form_review_delete.php" method="POST">
        <input type="hidden" name="id" value="<?= $id ?>">
        
        <div class="review-delete-buttons">
            <button type="submit" name="delete_review" class="btn-confirm-delete">
                Confirmar
            </button>
            
            <a href="/student025/shop/backend/reviews.php" class="btn-cancel-delete">
                Cancelar
            </a>
        </div>
    </form>
</div>

<?php include($root_dir . 'footer.php'); ?>