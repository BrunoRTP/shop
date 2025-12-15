<?php
session_start();
header('Content-Type: application/json');

$root_dir = $_SERVER['DOCUMENT_ROOT'] . '/student025/shop/backend/';
include($root_dir . 'db_connection.php'); 

// Verificar si hay sesión
if(!isset($_SESSION['user_id'])){
    echo json_encode([
        'success' => false,
        'message' => 'No hay sesión activa'
    ]);
    exit;
}

$product_id = isset($_POST['id']) ? intval($_POST['id']) : (isset($_GET['id']) ? intval($_GET['id']) : 0);
$customer_id = $_SESSION['user_id'];

if($product_id <= 0) {
    echo json_encode([
        'success' => false,
        'message' => 'ID de producto inválido'
    ]);
    exit;
}

// Verificar si el producto existe y hay stock
$sql_check_product = "SELECT stock FROM 025_products WHERE id = $product_id";
$result_product = mysqli_query($conn, $sql_check_product);

if(mysqli_num_rows($result_product) == 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Producto no encontrado'
    ]);
    exit;
}

$product = mysqli_fetch_assoc($result_product);
if($product['stock'] <= 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Producto sin stock'
    ]);
    exit;
}

// Verificar si ya está en el carrito
$sql_check = "SELECT quantity FROM 025_cart 
              WHERE customer_id = $customer_id AND product_id = $product_id";

$result_check = mysqli_query($conn, $sql_check);

if(mysqli_num_rows($result_check) > 0) {
    // Actualizar cantidad
    $sql = "UPDATE 025_cart 
            SET quantity = quantity + 1 
            WHERE customer_id = $customer_id AND product_id = $product_id";
    
    $result = mysqli_query($conn, $sql);
    
    if($result && mysqli_affected_rows($conn) > 0){
        echo json_encode([
            'success' => true,
            'message' => 'Cantidad actualizada',
            'action' => 'updated'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Error al actualizar'
        ]);
    }
    
} else {
    // Insertar nuevo
    $sql = "INSERT INTO 025_cart (customer_id, product_id, quantity) 
            VALUES ($customer_id, $product_id, 1)";
    
    $result = mysqli_query($conn, $sql);
    
    if($result && mysqli_affected_rows($conn) > 0){
        echo json_encode([
            'success' => true,
            'message' => 'Producto añadido al carrito',
            'action' => 'inserted'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Error al añadir al carrito'
        ]);
    }
}

mysqli_close($conn);
?>