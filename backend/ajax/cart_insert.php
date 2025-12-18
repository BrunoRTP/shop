<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

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
        'message' => 'Error de conexión'
    ]);
    exit();
}

mysqli_set_charset($conn, "utf8");

// SI NO HAY SESIÓN, CREAR UNA AUTOMÁTICAMENTE CON EL USUARIO INVITADO
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
        // Crear usuario invitado si no existe
        $password_hash = password_hash('guest123', PASSWORD_DEFAULT);
        $sql_create = "INSERT INTO 025_customers (username, password_hash, type_client) 
                       VALUES ('invitado', '$password_hash', 'guest')";
        
        if(mysqli_query($conn, $sql_create)) {
            $user_id = mysqli_insert_id($conn);
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = 'invitado';
            $_SESSION['type_client'] = 'guest';
            $_SESSION['is_guest'] = true;
        }
    }
}

$product_id = isset($_POST['id']) ? intval($_POST['id']) : (isset($_GET['id']) ? intval($_GET['id']) : 0);
$customer_id = $_SESSION['user_id'];

if($product_id <= 0) {
    echo json_encode([
        'success' => false,
        'message' => 'ID de producto inválido'
    ]);
    mysqli_close($conn);
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
    mysqli_close($conn);
    exit;
}

$product = mysqli_fetch_assoc($result_product);
if($product['stock'] <= 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Producto sin stock'
    ]);
    mysqli_close($conn);
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