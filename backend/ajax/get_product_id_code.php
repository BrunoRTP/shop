<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$root_dir = $_SERVER['DOCUMENT_ROOT'] . '/student025/shop/backend/';
include($root_dir . 'db_connection.php');

if (!isset($_GET['product_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'product_id no proporcionado'
    ]);
    exit;
}

$product_id = intval($_GET['product_id']);

$sql = "SELECT id_code FROM 025_products WHERE id = $product_id LIMIT 1";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $producto = mysqli_fetch_assoc($result);
    
    if (!empty($producto['id_code'])) {
        echo json_encode([
            'success' => true,
            'id_code' => $producto['id_code']
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'El producto no tiene id_code asignado',
            'product_id' => $product_id
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Producto no encontrado',
        'product_id' => $product_id
    ]);
}

mysqli_close($conn);
?>