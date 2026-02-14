<?php
// Cabeceras CORS
header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
header('Content-Type: application/json; charset=UTF-8');

// Manejar preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Conexión a la base de datos
$root_dir = $_SERVER['DOCUMENT_ROOT'] . '/student025/shop/backend/';
require_once($root_dir . 'db_connection.php');

// Solo aceptar POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Solo se permite el método POST']);
    exit;
}

// Capturar datos del pedido recibido
$id_code = isset($_POST['id_code']) ? mysqli_real_escape_string($conn, $_POST['id_code']) : '';
$email = isset($_POST['email']) ? mysqli_real_escape_string($conn, $_POST['email']) : '';
$address = isset($_POST['address']) ? mysqli_real_escape_string($conn, $_POST['address']) : '';
$quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

// Validar que tenemos los datos necesarios
if (empty($id_code) || empty($email) || empty($address) || $quantity <= 0) {
    echo json_encode([
        'success' => false, 
        'message' => 'Datos insuficientes. Se requiere: id_code, email, address, quantity',
        'recibido' => [
            'id_code' => $id_code,
            'email' => $email,
            'address' => $address,
            'quantity' => $quantity
        ]
    ]);
    exit;
}

// Buscar el producto en nuestra base de datos usando el id que coincide con su id_code
// El id_code del proveedor externo es nuestro id local
$sql_product = "SELECT id, name, price FROM 025_products WHERE id = '$id_code' LIMIT 1";
$result_product = mysqli_query($conn, $sql_product);

if (!$result_product || mysqli_num_rows($result_product) == 0) {
    echo json_encode([
        'success' => false, 
        'message' => 'Producto no encontrado con id_code: ' . $id_code,
        'sql' => $sql_product
    ]);
    exit;
}

$producto = mysqli_fetch_assoc($result_product);
$product_id = $producto['id'];
$product_name = $producto['name'];
$unit_price = floatval($producto['price']);

// Calcular precio total
$total_price = $unit_price * $quantity;

// Insertar el pedido en la tabla 025_order
// customer_id se deja NULL porque es un pedido externo
$sql_insert = "INSERT INTO 025_order (customer_id, product_id, quantity, price, address, email, order_date) 
               VALUES (NULL, $product_id, $quantity, $total_price, '$address', '$email', NOW())";

if (mysqli_query($conn, $sql_insert)) {
    $order_id = mysqli_insert_id($conn);
    
    // Opcional: Reducir el stock del producto
    $sql_update_stock = "UPDATE 025_products SET stock = stock - $quantity WHERE id = $product_id";
    mysqli_query($conn, $sql_update_stock);
    
    echo json_encode([
        'success' => true, 
        'message' => 'Pedido registrado correctamente',
        'order_id' => $order_id,
        'detalles' => [
            'producto' => $product_name,
            'cantidad' => $quantity,
            'precio_unitario' => $unit_price,
            'total' => $total_price,
            'email' => $email,
            'direccion' => $address
        ]
    ]);
} else {
    echo json_encode([
        'success' => false, 
        'message' => 'Error al insertar el pedido en la base de datos',
        'error_sql' => mysqli_error($conn)
    ]);
}

mysqli_close($conn);
?>