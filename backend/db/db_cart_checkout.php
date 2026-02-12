<?php
session_start();
$root_dir = $_SERVER['DOCUMENT_ROOT'] . '/student025/shop/backend/';
include($root_dir. 'header.php'); 
include($root_dir . 'db_connection.php'); 

// Incluir PHPMailer
require_once $_SERVER['DOCUMENT_ROOT'].'/PHPMailer/src/Exception.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/PHPMailer/src/PHPMailer.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// 1. Verificar si el usuario está logueado
if(!isset($_SESSION['user_id'])){
    header("Location: /student025/shop/backend/form_login.php");     
    exit; 
}

// 2. Capturar la dirección que viene de cart.php
$address = isset($_GET['address']) ? mysqli_real_escape_string($conn, $_GET['address']) : '';

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
$sql = "SELECT c.product_id, c.quantity, p.name, p.price 
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

// 4. Configurar fecha y variables
date_default_timezone_set('Europe/Madrid');
$order_date = date('Y-m-d H:i:s');
$orders_created = 0;
$errors = [];

// Obtener datos del usuario
$sql_customer = "SELECT email, username FROM 025_customers WHERE id = $customer_id";
$result_customer = mysqli_query($conn, $sql_customer);
$customer_data = mysqli_fetch_assoc($result_customer);
$customer_email = $customer_data['email'] ?? '';
$customer_name = $customer_data['username'] ?? 'Cliente';

// Array para almacenar productos del pedido
$products_for_email = [];
$subtotal = 0;

// 5. Procesar cada producto del carrito (Pasar a tabla de pedidos)
while($item = mysqli_fetch_assoc($result)){
    $product_id = $item['product_id'];
    $quantity = $item['quantity'];
    $price = $item['price'] * $quantity;
    
    $products_for_email[] = [
        'name' => $item['name'],
        'quantity' => $quantity,
        'price' => $item['price']
    ];
    
    $subtotal += $price;
    
    $sql_insert = "INSERT INTO 025_order (customer_id, product_id, quantity, price, address, email, order_date) 
                   VALUES ($customer_id, $product_id, $quantity, $price, '$address', '$customer_email', '$order_date')";
    
    if(mysqli_query($conn, $sql_insert)){
        $orders_created++;
    } else {
        $errors[] = "Error al crear pedido para producto ID $product_id: " . mysqli_error($conn);
    }
}

// 6. Calcular IVA y total
$tax = $subtotal * 0.21; 
$total = $subtotal + $tax;
$order_number = 'PED-' . date('Ymd-His') . '-' . $customer_id;

// 7. VACIAR EL CARRITO 
// Importante: Solo si se han creado los registros de pedido con éxito
if($orders_created > 0 && empty($errors)) {
    $sql_empty = "DELETE FROM 025_cart WHERE customer_id = $customer_id";
    mysqli_query($conn, $sql_empty);
}

// 8. ENVIAR EMAIL
$email_sent = false;
if($orders_created > 0 && empty($errors) && !empty($customer_email)){
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = "smtp.remotehost.es";
        $mail->SMTPAuth = true;
        $mail->Username = "no-reply@remotehost.es";
        $mail->Password = "Justfortesting26#";
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->CharSet = 'UTF-8';
        
        $mail->setFrom('no-reply@remotehost.es', 'RemoteHost');
        $mail->addAddress($customer_email, $customer_name);
        
        // Generar contenido HTML del email
        $productsHTML = '';
        foreach($products_for_email as $product) {
            $itemTotal = $product['price'] * $product['quantity'];
            $productsHTML .= "<tr><td>{$product['name']}</td><td>{$product['quantity']}</td><td>" . number_format($itemTotal, 2) . " €</td></tr>";
        }
        
        $mail->isHTML(true);
        $mail->Subject = "Pedido recibido #{$order_number}";
        $mail->Body = "<h1>Gracias por tu compra, {$customer_name}</h1>
                       <p>Tu pedido <strong>{$order_number}</strong> ha sido procesado.</p>
                       <p>Dirección de envío: {$address}</p>
                       <table border='1' cellpadding='10'>
                         <thead><tr><th>Producto</th><th>Cant.</th><th>Subtotal</th></tr></thead>
                         <tbody>{$productsHTML}</tbody>
                         <tfoot><tr><td colspan='2'>Total (inc. IVA):</td><td>" . number_format($total, 2) . " €</td></tr></tfoot>
                       </table>";
        
        $mail->send();
        $email_sent = true;
    } catch (Exception $e) {
        error_log("Error al enviar email: {$mail->ErrorInfo}");
    }
}

// 9. INTERFAZ DE ÉXITO
?>

<div class="container" style="text-align: center; padding: 40px 20px;">
    <?php if($orders_created > 0 && empty($errors)): ?>
        <h2 style="color: #28a745;">¡Pedido Completado!</h2>
        <p>Tu solicitud ha sido procesada correctamente</p>
        <p>El carrito se ha vaciado y hemos enviado los detalles a <strong><?= htmlspecialchars($customer_email) ?></strong>.</p>
    <?php else: ?>
        <h2 style="color: #dc3545;">Hubo un problema</h2>
        <p>No se pudo completar el pedido. Por favor, contacta con soporte.</p>
    <?php endif; ?>
    
    <br><br>
    <a href="/student025/shop/backend/products.php" style="padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px;">
        Volver a la tienda
    </a>
</div>

<?php 
mysqli_close($conn);
include($root_dir . 'footer.php'); 
?>