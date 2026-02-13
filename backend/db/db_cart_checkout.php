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

// 5. Procesar cada producto del carrito
while($item = mysqli_fetch_assoc($result)){
    $product_id = $item['product_id'];
    $quantity = $item['quantity'];
    $price = $item['price'] * $quantity;
    
    // Guardar para el email
    $products_for_email[] = [
        'name' => $item['name'],
        'quantity' => $quantity,
        'price' => $item['price']
    ];
    
    $subtotal += $price;
    
    // Insertar pedido
    $sql_insert = "INSERT INTO 025_order (customer_id, product_id, quantity, price, address, email, order_date) 
                   VALUES ($customer_id, $product_id, $quantity, $price, '$address', '$customer_email', '$order_date')";
    
    if(mysqli_query($conn, $sql_insert)){
        $orders_created++;
    } else {
        $errors[] = "Error al crear pedido para producto ID $product_id: " . mysqli_error($conn);
    }
}

// 6. Calcular IVA y total
$tax = $subtotal * 0.21; // 21% IVA
$total = $subtotal + $tax;

// 7. Generar número de pedido único
$order_number = 'PED-' . date('Ymd-His') . '-' . $customer_id;

// 8. ENVIAR EMAIL
if($orders_created > 0 && empty($errors) && !empty($customer_email)){
    $mail = new PHPMailer(true);
    
    try {
        // Configuración SMTP
        $mail->isSMTP();
        $mail->Host = "smtp.remotehost.es";
        $mail->SMTPAuth = true;
        $mail->Username = "no-reply@remotehost.es";
        $mail->Password = "Justfortesting26#";
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->CharSet = 'UTF-8';
        
        // Remitente y destinatario
        $mail->setFrom('no-reply@remotehost.es', 'RemoteHost');
        $mail->addAddress($customer_email, $customer_name);
        
        // Leer CSS
        $emailCSS = file_get_contents($root_dir . '../css/email-pedido-styles.css');
        
        // Generar HTML de productos
        $productsHTML = '';
        foreach($products_for_email as $product) {
            $itemTotal = $product['price'] * $product['quantity'];
            $productsHTML .= "
            <tr class='product-row'>
                <td class='product-name'>{$product['name']}</td>
                <td class='product-quantity'>{$product['quantity']}</td>
                <td class='product-price'>" . number_format($product['price'], 2) . " €</td>
                <td class='product-total'>" . number_format($itemTotal, 2) . " €</td>
            </tr>";
        }
        
        // HTML del email
        $emailHTML = "
        <!DOCTYPE html>
        <html lang='es'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <style>
            {$emailCSS}
            </style>
        </head>
        <body>
            <div class='email-container'>
                <div class='email-header'>
                    <h1>Gracias por tu pedido</h1>
                    <p class='subtitle'>Hemos recibido tu pedido correctamente</p>
                </div>
                
                <div class='email-body'>
                    <div class='greeting-section'>
                        <p class='greeting'>Hola {$customer_name},</p>
                        <p>Tu pedido ha sido registrado y lo procesaremos lo antes posible. A continuación encontrarás todos los detalles:</p>
                    </div>
                    
                    <div class='info-box'>
                        <h2>Información del pedido</h2>
                        <div class='info-row'>
                            <span class='label'>Número de pedido:</span>
                            <span class='value order-number'>{$order_number}</span>
                        </div>
                        <div class='info-row'>
                            <span class='label'>Fecha:</span>
                            <span class='value'>" . date('d/m/Y H:i', strtotime($order_date)) . "</span>
                        </div>
                        <div class='info-row'>
                            <span class='label'>Cliente:</span>
                            <span class='value'>{$customer_name}</span>
                        </div>
                        <div class='info-row'>
                            <span class='label'>Email:</span>
                            <span class='value'>{$customer_email}</span>
                        </div>
                        <div class='info-row'>
                            <span class='label'>Dirección de envío:</span>
                            <span class='value'>{$address}</span>
                        </div>
                    </div>
                    
                    <div class='products-section'>
                        <h2>Productos del pedido</h2>
                        <table class='products-table'>
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th>Precio unitario</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                {$productsHTML}
                            </tbody>
                        </table>
                    </div>
                    
                    <div class='totals-section'>
                        <div class='total-row'>
                            <span class='total-label'>Subtotal:</span>
                            <span class='total-value'>" . number_format($subtotal, 2) . " €</span>
                        </div>
                        <div class='total-row'>
                            <span class='total-label'>IVA (21%):</span>
                            <span class='total-value'>" . number_format($tax, 2) . " €</span>
                        </div>
                        <div class='total-row grand-total'>
                            <span class='total-label'>TOTAL:</span>
                            <span class='total-value'>" . number_format($total, 2) . " €</span>
                        </div>
                    </div>
                    
                    <div class='info-alert'>
                        <h3>📦 Próximos pasos</h3>
                        <p>Procesaremos tu pedido en las próximas 24-48 horas. Recibirás un nuevo email cuando tu pedido sea enviado con el número de seguimiento.</p>
                    </div>
                    
                    <div class='help-section'>
                        <p>Si tienes alguna pregunta sobre tu pedido, no dudes en contactarnos:</p>
                        <p><strong>Email:</strong> info@remotehost.es | <strong>Tel:</strong> +34 900 123 456</p>
                    </div>
                </div>
                
                <div class='email-footer'>
                    <p><strong>RemoteHost</strong></p>
                    <p class='footer-text'>Este es un email automático, por favor no respondas a este mensaje.</p>
                    <p class='legal'>RemoteHost © 2026 - Todos los derechos reservados</p>
                </div>
            </div>
        </body>
        </html>
        ";
        
        // Configurar email
        $mail->isHTML(true);
        $mail->Subject = "Pedido recibido #{$order_number} - RemoteHost";
        $mail->Body = $emailHTML;
        $mail->AltBody = "Hola {$customer_name}, hemos recibido tu pedido #{$order_number}. Total: " . number_format($total, 2) . " €. Gracias por tu compra.";
        
        $mail->send();
        $email_sent = true;
    } catch (Exception $e) {
        $email_sent = false;
        error_log("Error al enviar email del pedido: {$mail->ErrorInfo}");
    }
}


mysqli_close($conn);
include($root_dir . 'footer.php'); 

// 9. INTERFAZ DE ÉXITO
?>

<div class="container" style="text-align: center; padding: 40px 20px;">
    <?php if($orders_created > 0 && empty($errors)): ?>
        <h2 style="color: #28a745;">¡Pedido Completado!</h2>
        <p>Tu solicitud ha sido procesada correctamente con el número <strong><?= $order_number ?></strong>.</p>
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