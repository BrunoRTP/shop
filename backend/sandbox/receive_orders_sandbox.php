<?php
// Activar errores de PHP para debug
ini_set('display_errors', 1);
error_reporting(E_ALL);

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

// Verificar conexión
if (!$conn) {
    echo json_encode([
        'success' => false,
        'message' => 'Error de conexión a la base de datos',
        'error' => mysqli_connect_error()
    ]);
    exit;
}

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

// DEBUG: Log de datos recibidos
$debug_info = [
    'datos_recibidos' => [
        'id_code' => $id_code,
        'email' => $email,
        'address' => $address,
        'quantity' => $quantity
    ]
];

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

// ⭐ SANDBOX: Buscar por id_code = id_code (no por id = id_code)
$sql_product = "SELECT id, name, price, id_code FROM 025_products WHERE id_code = '$id_code' LIMIT 1";
$debug_info['sql_product'] = $sql_product;

$result_product = mysqli_query($conn, $sql_product);

if (!$result_product) {
    echo json_encode([
        'success' => false, 
        'message' => 'Error en la consulta SQL',
        'error_sql' => mysqli_error($conn),
        'debug' => $debug_info
    ]);
    exit;
}

if (mysqli_num_rows($result_product) == 0) {
    echo json_encode([
        'success' => false, 
        'message' => 'Producto no encontrado con id_code: ' . $id_code,
        'sql' => $sql_product,
        'debug' => $debug_info
    ]);
    exit;
}

$producto = mysqli_fetch_assoc($result_product);
$product_id = $producto['id'];
$product_name = $producto['name'];
$unit_price = floatval($producto['price']);

// Calcular precio total
$total_price = $unit_price * $quantity;

$debug_info['producto_encontrado'] = [
    'id' => $product_id,
    'name' => $product_name,
    'price' => $unit_price,
    'total_calculado' => $total_price
];

// Insertar el pedido en la tabla 025_order
$sql_insert = "INSERT INTO 025_order (customer_id, product_id, quantity, price, address, email, order_date) 
               VALUES (NULL, $product_id, $quantity, $total_price, '$address', '$email', NOW())";

$debug_info['sql_insert'] = $sql_insert;

if (mysqli_query($conn, $sql_insert)) {
    $order_id = mysqli_insert_id($conn);
    
    // Reducir el stock del producto (opcional - se puede comentar si no se quiere)
    $sql_update_stock = "UPDATE 025_products SET stock = stock - $quantity WHERE id = $product_id";
    mysqli_query($conn, $sql_update_stock);
    
    echo json_encode([
        'success' => true, 
        'message' => '✓ SANDBOX: Pedido registrado correctamente',
        'order_id' => $order_id,
        'modo' => 'SANDBOX (búsqueda por id_code)',
        'detalles' => [
            'producto' => $product_name,
            'id_code_recibido' => $id_code,
            'product_id_encontrado' => $product_id,
            'cantidad' => $quantity,
            'precio_unitario' => $unit_price,
            'total' => $total_price,
            'email' => $email,
            'direccion' => $address
        ],
        'debug' => $debug_info
    ]);
} else {
    echo json_encode([
        'success' => false, 
        'message' => 'Error al insertar el pedido en la base de datos',
        'error_sql' => mysqli_error($conn),
        'debug' => $debug_info
    ]);
}

mysqli_close($conn);
?>