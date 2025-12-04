<?php
session_start();
header('Content-Type: application/json');

$root_dir = $_SERVER['DOCUMENT_ROOT'] . '/student025/shop/backend/';
include($root_dir . 'db_connection.php');

if(!isset($_SESSION['user_id'])){
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit;
}

$customer_id = $_SESSION['user_id'];
$product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
$action = isset($_POST['action']) ? $_POST['action'] : '';

if($product_id <= 0 || !in_array($action, ['add', 'remove'])){
    echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
    exit;
}

if($action === 'add') {
    $sql = "UPDATE 025_cart 
            SET quantity = quantity + 1 
            WHERE customer_id = $customer_id AND product_id = $product_id";
    mysqli_query($conn, $sql);
}
else if($action === 'remove') {
    $sql_check = "SELECT quantity FROM 025_cart 
                  WHERE customer_id = $customer_id AND product_id = $product_id";
    $result_check = mysqli_query($conn, $sql_check);
    $row = mysqli_fetch_assoc($result_check);
    
    if($row && $row['quantity'] > 1) {
        $sql = "UPDATE 025_cart 
                SET quantity = quantity - 1 
                WHERE customer_id = $customer_id AND product_id = $product_id";
        mysqli_query($conn, $sql);
    } else {
        $sql = "DELETE FROM 025_cart 
                WHERE customer_id = $customer_id AND product_id = $product_id";
        mysqli_query($conn, $sql);
    }
}

$sql_item = "SELECT c.quantity, p.price 
             FROM 025_cart c 
             INNER JOIN 025_products p ON c.product_id = p.id
             WHERE c.customer_id = $customer_id AND c.product_id = $product_id";

$result_item = mysqli_query($conn, $sql_item);
$item = mysqli_fetch_assoc($result_item);

$quantity = $item ? $item['quantity'] : 0;
$subtotal = $item ? number_format($item['quantity'] * $item['price'], 2) : '0.00';

$sql_total = "SELECT SUM(c.quantity * p.price) as total 
              FROM 025_cart c 
              INNER JOIN 025_products p ON c.product_id = p.id
              WHERE c.customer_id = $customer_id";

$result_total = mysqli_query($conn, $sql_total);
$total_row = mysqli_fetch_assoc($result_total);
$total = number_format($total_row['total'] ?? 0, 2);

mysqli_close($conn);

echo json_encode([
    'success' => true,
    'quantity' => $quantity,
    'subtotal' => $subtotal,
    'total' => $total
]);
?>