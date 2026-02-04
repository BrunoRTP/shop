<?php

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
include_once '../db_connection.php';
// Forzar zona horaria de España
date_default_timezone_set('Europe/Madrid');
$api_key = $_GET['api_key'] ?? '';

$sql = "Select * FROM 025_sellers WHERE api_key = '$api_key'";

$result = mysqli_query($conn, $sql);

$seller = mysqli_fetch_assoc($result);

$seller_id = $seller['seller_id'] ?? '';

// Obtener los 5 primeros productos
$sql = "SELECT id, name, price 
        FROM 025_products 
        where id in (SELECT product_id FROM 025_products_sellers WHERE seller_id = '$seller_id')
        ";

$result = mysqli_query($conn, $sql);

$products = mysqli_fetch_all($result, MYSQLI_ASSOC);
$products_json = json_encode($products, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
echo $products_json;

if(!$result) {
    echo json_encode([
        'success' => false,
        'message' => 'Error en la consulta: ' . mysqli_error($conn)
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    mysqli_close($conn);
    exit;
}

?>