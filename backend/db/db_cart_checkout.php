<?php
session_start();
$root_dir = $_SERVER['DOCUMENT_ROOT'] . '/student025/shop/backend/';
include($root_dir. 'header.php'); 
include($root_dir . 'db_connection.php'); 

// 1. Verificar si el usuario está logueado
if(!isset($_SESSION['user_id'])){
    header("Location: /student025/shop/backend/form_login.php");     
    exit; 
}

// 2. Capturar la dirección que viene de cart.php (vía JavaScript)
// Usamos mysqli_real_escape_string para evitar inyecciones SQL y errores por comillas
$address = isset($_GET['address']) ? mysqli_real_escape_string($conn, $_GET['address']) : '';

// Si la dirección está vacía, detenemos el proceso
if(empty($address)){
    echo "<div class='container'>";
    echo "<h2>Error: La dirección de envío es obligatoria.</h2>";
    echo "<p>Por favor, vuelve al carrito e introduce una dirección.</p>";
    echo "<br><a href='/student025/shop/backend/cart.php' class='btn'>Volver al carrito</a>";
    echo "</div>";
    include($root_dir . 'footer.php');
    exit;
}

$customer_id = $_SESSION['user_id'];

// 3. Obtener los productos que están en el carrito del usuario
$sql = "SELECT c.product_id, c.quantity, p.price 
        FROM 025_cart c
        JOIN 025_products p ON c.product_id = p.id
        WHERE c.customer_id = $customer_id";

$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) == 0){
    echo "<div class='container'>";
    echo "Tu carrito está vacío. No hay nada que procesar.";
    echo "<br><a href='/student025/shop/backend/products.php'>Ver productos</a>";
    echo "</div>";
    include($root_dir . 'footer.php');
    exit;
}

// 4. Configurar fecha y variables de control
date_default_timezone_set('Europe/Madrid');
$order_date = date('Y-m-d H:i:s');
$orders_created = 0;
$errors = [];

// 5. Procesar cada producto del carrito y convertirlo en pedido (order)
while($item = mysqli_fetch_assoc($result)){
    $product_id = $item['product_id'];
    $quantity = $item['quantity'];
    $price = $item['price'] * $quantity;
    
    // Insertamos incluyendo la dirección capturada
    $sql_insert = "INSERT INTO 025_order (customer_id, product_id, quantity, price, address, order_date) 
                   VALUES ($customer_id, $product_id, $quantity, $price, '$address', '$order_date')";
    
    if(mysqli_query($conn, $sql_insert)){
        $orders_created++;
    } else {
        $errors[] = "Error al crear pedido para producto ID $product_id: " . mysqli_error($conn);
    }
}

// 6. Si todo ha ido bien, vaciamos el carrito y mostramos confirmación
echo "<div class='container'>";
if($orders_created > 0 && empty($errors)){
    $sql_clear = "DELETE FROM 025_cart WHERE customer_id = $customer_id";
    mysqli_query($conn, $sql_clear);
    
    echo "<h2>¡Pedido completado exitosamente!</h2>";
    echo "<p>Se han procesado $orders_created productos correctamente.</p>";
    echo "<p><strong>Dirección de entrega:</strong> " . htmlspecialchars($_GET['address']) . "</p>";
    echo "<p>Fecha: " . date('d/m/Y H:i', strtotime($order_date)) . "</p>";
    echo "<p>Tu carrito ha sido vaciado.</p>";
    echo "<br><a href='/student025/shop/backend/orders.php'>Ver mis pedidos</a>";
    echo " | <a href='/student025/shop/backend/products.php'>Seguir comprando</a>";
} else {
    echo "<h2>Hubo problemas al procesar tu pedido</h2>";
    if(!empty($errors)){
        echo "<p>Errores encontrados:</p><ul>";
        foreach($errors as $error){
            echo "<li>$error</li>";
        }
        echo "</ul>";
    }
    echo "<br><a href='/student025/shop/backend/cart.php'>Volver al carrito para reintentar</a>";
}
echo "</div>";

mysqli_close($conn);
include($root_dir . 'footer.php'); 
?>