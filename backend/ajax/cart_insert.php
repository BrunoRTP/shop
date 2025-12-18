<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit();
}

$root_dir = $_SERVER['DOCUMENT_ROOT'] . '/student025/shop/backend/';
include($root_dir . 'db_connection.php');

// Si no hay sesión, crear una con el usuario invitado
if(!isset($_SESSION['user_id'])){
    $sql = "SELECT * FROM 025_customers WHERE username = 'invitado' LIMIT 1";
    $result = mysqli_query($conn, $sql);
    
    if($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['type_client'] = $user['type_client'];
        $_SESSION['is_guest'] = true;
    }
}

$product_id = isset($_POST['id']) ? intval($_POST['id']) : (isset($_GET['id']) ? intval($_GET['id']) : 0);
$customer_id = $_SESSION['user_id'];

if($product_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'ID inválido']);
    mysqli_close($conn);
    exit;
}

// Verificar stock
$sql = "SELECT stock FROM 025_products WHERE id = $product_id";
$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) == 0) {
    echo json_encode(['success' => false, 'message' => 'Producto no encontrado']);
    mysqli_close($conn);
    exit;
}

$product = mysqli_fetch_assoc($result);
if($product['stock'] <= 0) {
    echo json_encode(['success' => false, 'message' => 'Sin stock']);
    mysqli_close($conn);
    exit;
}

// Verificar si ya está en el carrito
$sql = "SELECT quantity FROM 025_cart WHERE customer_id = $customer_id AND product_id = $product_id";
$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) > 0) {
    // Actualizar cantidad
    $sql = "UPDATE 025_cart SET quantity = quantity + 1 WHERE customer_id = $customer_id AND product_id = $product_id";
    mysqli_query($conn, $sql);
    $action = 'updated';
} else {
    // Insertar nuevo
    $sql = "INSERT INTO 025_cart (customer_id, product_id, quantity) VALUES ($customer_id, $product_id, 1)";
    mysqli_query($conn, $sql);
    $action = 'inserted';
}

mysqli_close($conn);
echo json_encode(['success' => true, 'message' => 'Producto añadido', 'action' => $action]);
?>