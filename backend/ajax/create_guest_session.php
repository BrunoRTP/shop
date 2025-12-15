<?php
session_start();
header('Content-Type: application/json');

$root_dir = $_SERVER['DOCUMENT_ROOT'] . '/student025/shop/backend/';
include($root_dir . 'db_connection.php');

// Función para obtener un usuario invitado
function getOrCreateGuestUser($conn) {
    // Buscar usuario invitado
    $sql = "SELECT * FROM 025_customers WHERE username = 'invitado'";
    $result = mysqli_query($conn, $sql);

    return mysqli_fetch_assoc($result);

}

// Si no hay sesión activa, crear una de invitado
if(!isset($_SESSION['user_id'])){
    $user = getOrCreateGuestUser($conn);
    
    if($user){
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['type_client'] = $user['type_client'];
        $_SESSION['is_guest'] = true;
        
        echo json_encode([
            'success' => true,
            'message' => 'Sesión de invitado creada',
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
            'message' => 'Error al crear usuario invitado'
        ]);
    }
} else {
    // Ya hay sesión activa
    echo json_encode([
        'success' => true,
        'message' => 'Sesión ya existe',
        'user' => [
            'id' => $_SESSION['user_id'],
            'username' => $_SESSION['username'],
            'type_client' => $_SESSION['type_client'],
            'is_guest' => isset($_SESSION['is_guest']) ? $_SESSION['is_guest'] : false
        ]
    ]);
}

mysqli_close($conn);
?>