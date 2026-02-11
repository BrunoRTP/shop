<?php
$root_dir = $_SERVER['DOCUMENT_ROOT'] . '/student025/shop/backend/';
include($root_dir . 'db_connection.php');

header('Content-Type: application/json');

if (!isset($_GET['product_id'])) {
    echo json_encode(['error' => 'product_id no proporcionado']);
    exit;
}

$product_id = intval($_GET['product_id']);

$sql = "SELECT supplier_id FROM 025_products WHERE id = $product_id";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $producto = mysqli_fetch_assoc($result);
    echo json_encode([
        'success' => true,
        'supplier_id' => $producto['supplier_id']
    ]);
} else {
    echo json_encode([
        'success' => false,
        'error' => 'Producto no encontrado'
    ]);
}

mysqli_close($conn);
?>