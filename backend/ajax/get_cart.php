<?php
// backend/ajax/get_cart.php
session_start();

// Headers CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

// Manejar peticiones OPTIONS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Conectar a la base de datos SIN incluir header ni footer
$conn = mysqli_connect('localhost', 'root', '', 'shop');
if(!$conn){
    echo json_encode([
        'success' => false,
        'message' => 'Error de conexión: ' . mysqli_connect_error(),
        'items' => [],
        'total' => '0.00',
        'total_items' => 0
    ]);
    exit();
}

mysqli_set_charset($conn, "utf8");
mysqli_query($conn, "SET time_zone = '+01:00'");

// Verificar si hay sesión
if(!isset($_SESSION['user_id'])){
    echo json_encode([
        'success' => false,
        'message' => 'No hay sesión activa',
        'items' => [],
        'total' => '0.00',
        'total_items' => 0
    ]);
    mysqli_close($conn);
    exit;
}

$customer_id = $_SESSION['user_id'];

// Obtener productos del carrito con información completa
$sql = "SELECT c.product_id, c.quantity, 
               p.name, p.description, p.price, p.stock, p.image
        FROM 025_cart c
        INNER JOIN 025_products p ON c.product_id = p.id
        WHERE c.customer_id = $customer_id
        ORDER BY c.product_id ASC";

$result = mysqli_query($conn, $sql);

if(!$result) {
    echo json_encode([
        'success' => false,
        'message' => 'Error en la consulta: ' . mysqli_error($conn),
        'items' => [],
        'total' => '0.00',
        'total_items' => 0
    ]);
    mysqli_close($conn);
    exit;
}

$items = array();
$total = 0;
$total_items = 0;

while($row = mysqli_fetch_assoc($result)) {
    // Convertir imagen a base64 si existe
    $image_data = null;
    if(!empty($row['image'])) {
        $image_data = 'data:image/jpeg;base64,' . base64_encode($row['image']);
    }
    
    $subtotal = $row['quantity'] * $row['price'];
    $total += $subtotal;
    $total_items += $row['quantity'];
    
    $items[] = array(
        'product_id' => $row['product_id'],
        'name' => $row['name'],
        'description' => $row['description'],
        'price' => number_format($row['price'], 2),
        'price_raw' => $row['price'],
        'quantity' => intval($row['quantity']),
        'stock' => intval($row['stock']),
        'subtotal' => number_format($subtotal, 2),
        'image_data' => $image_data
    );
}

mysqli_close($conn);

// Devolver JSON limpio (sin HTML)
echo json_encode([
    'success' => true,
    'items' => $items,
    'total' => number_format($total, 2),
    'total_raw' => $total,
    'total_items' => $total_items
]);
?>