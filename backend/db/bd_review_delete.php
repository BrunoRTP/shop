<?php
$root_dir = $_SERVER['DOCUMENT_ROOT'] . '/student025/shop/backend/';
include($root_dir . 'auth_functions.php');
require_login();
include($root_dir. 'header.php'); 
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
    
    if(isset($_POST['delete_review'])){
        $id = intval($_POST['id']);
        
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
        
        // Usar prepared statement para mayor seguridad
        $sql = "DELETE FROM 025_reviews WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        
        if($stmt){
            mysqli_stmt_bind_param($stmt, "i", $id);
            $result = mysqli_stmt_execute($stmt);
            
            if($result){
                if (mysqli_stmt_affected_rows($stmt) > 0) {
                    echo "<div class='message-success'>";
                    echo "<h3>✅ Review eliminada exitosamente</h3>";
                    echo "<p>La review ha sido eliminada de forma permanente.</p>";
                    echo "<a href='/student025/shop/backend/reviews.php' style='padding: 10px 20px; background: #4CAF50; color: white; text-decoration: none; border-radius: 5px; display: inline-block; margin-top: 10px;'>Volver a Reviews</a>";
                    echo "</div>";
                } else {
                    echo "<div class='message-warning'>";
                    echo "<h3>⚠️ No se encontró la review</h3>";
                    echo "<p>Es posible que la review ya haya sido eliminada.</p>";
                    echo "<a href='/student025/shop/backend/reviews.php'>Volver</a>";
                    echo "</div>";
                }
            } else {
                echo "<div class='message-error'>";
                echo "<h3>Error en la eliminación</h3>";
                echo "<p>Error: " . htmlspecialchars(mysqli_error($conn)) . "</p>";
                echo "<a href='/student025/shop/backend/reviews.php'>Volver</a>";
                echo "</div>";
            }
            
            mysqli_stmt_close($stmt);
        } else {
            echo "<div class='message-error'>";
            echo "<h3>Error en la preparación</h3>";
            echo "<p>Error: " . htmlspecialchars(mysqli_error($conn)) . "</p>";
            echo "<a href='/student025/shop/backend/reviews.php'>Volver</a>";
            echo "</div>";
        }
    }
    
    mysqli_close($conn);
?>

<?php include($root_dir . 'footer.php'); ?>