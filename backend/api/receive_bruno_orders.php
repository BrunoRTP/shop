<?php
// 1. Cabeceras para evitar bloqueos de CORS y redirecciones
header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
header('Content-Type: application/json; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// 2. Conexión a la base de datos
$root_dir = $_SERVER['DOCUMENT_ROOT'] . '/student025/shop/backend/';
require_once($root_dir . 'db_connection.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Solo POST permitido']);
    exit;
}

// 3. Captura de datos enviados desde el JS
$id_code  = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
$email    = isset($_POST['email']) ? mysqli_real_escape_string($conn, $_POST['email']) : '';
$address  = isset($_POST['address']) ? mysqli_real_escape_string($conn, $_POST['address']) : '';
$quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

if ($id_code <= 0 || empty($email)) {
    echo json_encode(['success' => false, 'message' => 'Datos insuficientes (ID o Email)']);
    exit;
}

// 4. Buscar nombre y precio usando id_code
$sql_prod = "SELECT name, price FROM 025_products WHERE id_code = $id_code LIMIT 1";
$res_prod = mysqli_query($conn, $sql_prod);

if ($prod = mysqli_fetch_assoc($res_prod)) {
    $product_name = $prod['name'];
    $total_price = floatval($prod['price']) * $quantity;
} else {
    // Si no encuentra el id_code, usamos valores por defecto para no romper el insert
    $product_name = "Producto Externo ($id_code)";
    $total_price = 0; 
}

// 5. Insertar directamente en la tabla de pedidos
$sql_insert = "INSERT INTO 025_order (customer_id, product_id, quantity, price, address, email, order_date) 
               VALUES (NULL, $id_code, $quantity, $total_price, '$address', '$email', NOW())";

if (mysqli_query($conn, $sql_insert)) {
    echo json_encode([
        'success' => true, 
        'message' => 'Pedido registrado correctamente',
        'detalles' => "Producto: $product_name, Total: $total_price"
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error SQL: ' . mysqli_error($conn)]);
}

mysqli_close($conn);
?>