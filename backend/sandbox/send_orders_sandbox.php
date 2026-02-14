<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$root_dir = $_SERVER['DOCUMENT_ROOT'] . '/student025/shop/backend/';
include($root_dir . 'db_connection.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Solo POST permitido']);
    exit;
}

// Obtener datos del pedido
$product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
$email = isset($_POST['email']) ? $_POST['email'] : '';
$address = isset($_POST['address']) ? $_POST['address'] : '';
$quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

if ($product_id <= 0 || empty($email) || empty($address)) {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
    exit;
}

// Obtener el id_code del producto para enviarlo
$sql = "SELECT id_code, supplier_id FROM 025_products WHERE id = $product_id LIMIT 1";
$result = mysqli_query($conn, $sql);

if (!$result || mysqli_num_rows($result) == 0) {
    echo json_encode(['success' => false, 'message' => 'Producto no encontrado']);
    exit;
}

$producto = mysqli_fetch_assoc($result);
$id_code = $producto['id_code'];

// URL SANDBOX - apunta a tu propio servidor
$url_proveedor = 'https://remotehost.es/student025/shop/backend/sandbox/receive_orders_sandbox.php';

// Preparar datos para enviar
$datos_envio = [
    'id_code' => $id_code,
    'email' => $email,
    'address' => $address,
    'quantity' => $quantity
];

// Enviar pedido usando cURL
$ch = curl_init($url_proveedor);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($datos_envio));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Para desarrollo local

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curl_error = curl_error($ch);
curl_close($ch);

if ($http_code == 200) {
    $resultado = json_decode($response, true);
    
    if ($resultado && isset($resultado['success']) && $resultado['success']) {
        echo json_encode([
            'success' => true,
            'message' => '✓ SANDBOX: Pedido enviado exitosamente a ti mismo',
            'detalles' => $resultado,
            'debug' => [
                'id_code_enviado' => $id_code,
                'url' => $url_proveedor
            ]
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'El servidor respondió con error',
            'detalles' => $resultado,
            'response' => $response
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Error al conectar (HTTP ' . $http_code . ')',
        'curl_error' => $curl_error,
        'response' => $response
    ]);
}

mysqli_close($conn);
?>
