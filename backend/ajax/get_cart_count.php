<?php
session_start();
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit();
}

$root_dir = $_SERVER['DOCUMENT_ROOT'] . '/student025/shop/backend/';
include($root_dir . 'db_connection.php');

// Si no hay sesión, intentar crear una con el usuario invitado
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
        echo json_encode([
            'success' => false,
            'count' => 0,
            'message' => 'No hay sesión activa'
        ]);
        mysqli_close($conn);
        exit;
    }
}

$customer_id = $_SESSION['user_id'];

// Obtener cantidad total de productos en el carrito
$sql = "SELECT SUM(quantity) as total FROM 025_cart WHERE customer_id = $customer_id";
$result = mysqli_query($conn, $sql);

if($result) {
    $row = mysqli_fetch_assoc($result);
    $count = $row['total'] ? intval($row['total']) : 0;
    
    echo json_encode([
        'success' => true,
        'count' => $count
    ]);
} else {
    echo json_encode([
        'success' => false,
        'count' => 0,
        'message' => 'Error en la consulta'
    ]);
}

mysqli_close($conn);
?>