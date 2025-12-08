<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$root_dir = $_SERVER['DOCUMENT_ROOT'] . '/student025/shop/backend/';
include($root_dir . 'db_connection.php');

$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if($product_id <= 0) {
    echo json_encode(['error' => 'ID de producto inválido']);
    exit;
}

// Obtener el producto principal
$sql = "SELECT p.id, p.name, p.description, p.stock, p.price, c.name as category_name
        FROM 025_products p
        LEFT JOIN 025_categories c ON p.category_id = c.id
        WHERE p.id = $product_id";

$result = mysqli_query($conn, $sql);

if(!$result || mysqli_num_rows($result) == 0) {
    echo json_encode(['error' => 'Producto no encontrado']);
    exit;
}

$product = mysqli_fetch_assoc($result);

// Obtener productos similares (misma categoría, excluyendo el actual)
$category_id = isset($product['category_id']) ? $product['category_id'] : 0;
$sql_similar = "SELECT id, name, price
                FROM 025_products
                WHERE category_id = (SELECT category_id FROM 025_products WHERE id = $product_id)
                AND id != $product_id
                LIMIT 4";

$result_similar = mysqli_query($conn, $sql_similar);
$similar_products = array();

if($result_similar) {
    while($row = mysqli_fetch_assoc($result_similar)) {
        $similar_products[] = array(
            'id' => $row['id'],
            'name' => $row['name'],
            'price' => number_format($row['price'], 2)
        );
    }
}

// Construir respuesta
$response = array(
    'id' => $product['id'],
    'name' => $product['name'],
    'description' => $product['description'],
    'stock' => $product['stock'],
    'price' => number_format($product['price'], 2),
    'category' => $product['category_name'],
    'image' => '../assets/img/lamparaPie.jpg',
    'images' => array(
        '../assets/img/lamparaPieAlt1.png',
        '../assets/img/lamparaPieAlt2.png',
        '../assets/img/lamparaPieAlt3.png'
    ),
    'similar_products' => $similar_products
);

mysqli_close($conn);

echo json_encode($response);
?>