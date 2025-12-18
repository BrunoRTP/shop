<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Credentials: true');

// Manejar peticiones OPTIONS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Verificar si estamos en remoto o local para la conexión
$is_local = ($_SERVER['HTTP_HOST'] === 'localhost' || $_SERVER['HTTP_HOST'] === '127.0.0.1');

if ($is_local) {
    $conn = mysqli_connect('localhost', 'root', '', 'shop');
} else {
    $conn = mysqli_connect('remotehost.es', 'dwess1234', 'Usertest1234.', 'dwesdatabase');
}

if(!$conn){
    echo json_encode([
        'success' => false,
        'message' => 'Error de conexión: ' . mysqli_connect_error()
    ]);
    exit();
}

mysqli_set_charset($conn, "utf8");

// Si no hay sesión activa, crear una de invitado
if(!isset($_SESSION['user_id'])){
    // Buscar el usuario invitado en la base de datos
    $sql = "SELECT * FROM 025_customers WHERE username = 'invitado' LIMIT 1";
    $result = mysqli_query($conn, $sql);
    
    if($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        
        // Crear sesión con el usuario invitado
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
        // Si no existe el usuario invitado, crearlo
        $password_hash = password_hash('guest123', PASSWORD_DEFAULT);
        $sql_create = "INSERT INTO 025_customers (username, password_hash, type_client) 
                       VALUES ('invitado', '$password_hash', 'guest')";
        
        if(mysqli_query($conn, $sql_create)) {
            $user_id = mysqli_insert_id($conn);
            
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = 'invitado';
            $_SESSION['type_client'] = 'guest';
            $_SESSION['is_guest'] = true;
            
            echo json_encode([
                'success' => true,
                'message' => 'Usuario invitado creado y sesión iniciada',
                'user' => [
                    'id' => $user_id,
                    'username' => 'invitado',
                    'type_client' => 'guest',
                    'is_guest' => true
                ]
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Error al crear usuario invitado: ' . mysqli_error($conn)
            ]);
        }
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