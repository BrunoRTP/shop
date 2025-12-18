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

// Si ya hay sesión, devolverla
if(isset($_SESSION['user_id'])){
    echo json_encode([
        'success' => true,
        'message' => 'Sesión existente',
        'user' => [
            'id' => $_SESSION['user_id'],
            'username' => $_SESSION['username'],
            'type_client' => $_SESSION['type_client'],
            'is_guest' => isset($_SESSION['is_guest']) ? $_SESSION['is_guest'] : false
        ]
    ]);
    mysqli_close($conn);
    exit;
}

// Buscar usuario invitado
$sql = "SELECT * FROM 025_customers WHERE username = 'invitado' LIMIT 1";
$result = mysqli_query($conn, $sql);

if($result && mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
    
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['type_client'] = $user['type_client'];
    $_SESSION['is_guest'] = true;
    
    echo json_encode([
        'success' => true,
        'message' => 'Sesión creada',
        'user' => [
            'id' => $user['id'],
            'username' => $user['username'],
            'type_client' => $user['type_client'],
            'is_guest' => true
        ]
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Usuario invitado no encontrado'
    ]);
}

mysqli_close($conn);
?>