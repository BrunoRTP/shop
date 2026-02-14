<?php
session_start();
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit();
}

$root_dir = $_SERVER['DOCUMENT_ROOT'] . '/student025/shop/backend/';
include($root_dir . 'db_connection.php');

// Si no hay sesión, crear una con invitado
if(!isset($_SESSION['user_id'])){
    $sql = "SELECT * FROM 025_customers WHERE username = 'invitado' LIMIT 1";
    $result = mysqli_query($conn, $sql);
    
    if($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['type_client'] = $user['type_client'];
        $_SESSION['is_guest'] = true;
    } else {
        echo json_encode(['success' => false, 'message' => 'Sin sesión']);
        mysqli_close($conn);
        exit;
    }
}

$customer_id = intval($_SESSION['user_id']);
$product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
$action = isset($_POST['action']) ? $_POST['action'] : '';

if($product_id <= 0 || !in_array($action, ['add', 'remove'])){
    echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
    mysqli_close($conn);
    exit;
}

// Ejecutar acción
if($action === 'add') {
    $sql = "SELECT quantity FROM 025_cart WHERE customer_id = $customer_id AND product_id = $product_id";
    $result = mysqli_query($conn, $sql);
    
    if(mysqli_num_rows($result) > 0) {
        $sql = "UPDATE 025_cart SET quantity = quantity + 1 WHERE customer_id = $customer_id AND product_id = $product_id";
    } else {
        $sql = "INSERT INTO 025_cart (customer_id, product_id, quantity) VALUES ($customer_id, $product_id, 1)";
    }
    mysqli_query($conn, $sql);
} else {
    $sql = "SELECT quantity FROM 025_cart WHERE customer_id = $customer_id AND product_id = $product_id";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    
    if($row && $row['quantity'] > 1) {
        $sql = "UPDATE 025_cart SET quantity = quantity - 1 WHERE customer_id = $customer_id AND product_id = $product_id";
    } else {
        $sql = "DELETE FROM 025_cart WHERE customer_id = $customer_id AND product_id = $product_id";
    }
    mysqli_query($conn, $sql);
}

// Obtener información actualizada del item
$sql = "SELECT c.quantity, p.price FROM 025_cart c 
        INNER JOIN 025_products p ON c.product_id = p.id
        WHERE c.customer_id = $customer_id AND c.product_id = $product_id";
$result = mysqli_query($conn, $sql);
$item = mysqli_fetch_assoc($result);

$quantity = $item ? $item['quantity'] : 0;
$subtotal = $item ? round($item['quantity'] * $item['price'], 2) : 0;

// Obtener total del carrito
$sql = "SELECT SUM(c.quantity * p.price) as total, SUM(c.quantity) as total_items 
        FROM 025_cart c INNER JOIN 025_products p ON c.product_id = p.id
        WHERE c.customer_id = $customer_id";
$result = mysqli_query($conn, $sql);
$totals = mysqli_fetch_assoc($result);

mysqli_close($conn);

echo json_encode([
    'success' => true,
    'quantity' => $quantity,
    'subtotal' => round($subtotal, 2),  // Número sin formato
    'total' => round($totals['total'] ?? 0, 2),  // Número sin formato
    'total_items' => $totals['total_items'] ?? 0
]);
?>