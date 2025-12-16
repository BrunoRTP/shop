<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$root_dir = $_SERVER['DOCUMENT_ROOT'] . '/student025/shop/backend/';
include($root_dir . 'db_connection.php');

$sql = "SELECT id, name, description, stock, price, image 
        FROM 025_products 
        ORDER BY id ASC";

$result = mysqli_query($conn, $sql);
$products = array();

if($result) {
    while($row = mysqli_fetch_assoc($result)) {
        // Convertir imagen a base64 si existe
        $image_data = null;
        if(!empty($row['image'])) {
            $image_data = 'data:image/jpeg;base64,' . base64_encode($row['image']);
        }
        
        $products[] = array(
            'id' => $row['id'],
            'name' => $row['name'],
            'description' => $row['description'],
            'stock' => $row['stock'],
            'price' => number_format($row['price'], 2),
            'image_data' => $image_data
        );
    }
} else {
    echo json_encode(['error' => 'Error en la consulta: ' . mysqli_error($conn)]);
    exit;
}

mysqli_close($conn);

echo json_encode($products);
?>