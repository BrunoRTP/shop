<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include_once '../db_connection.php';

echo "1. Conexión a BD OK<br>";

$url_companero = "https://remotehost.es/student008/shop/backend/api/api_get_products.php?api_key=3333";

echo "2. URL definida<br>";

$json_data = file_get_contents($url_companero);

echo "3. JSON obtenido<br>";
echo "Contenido: " . htmlspecialchars(substr($json_data, 0, 200)) . "<br>";

$response = json_decode($json_data, true);

echo "4. JSON decodificado<br>";
var_dump($response);
echo "<br>";

$productos_externos = $response;

echo "5. Array productos extraído<br>";
echo "Total productos: " . count($productos_externos) . "<br>";

$supplier_id = 3; 
$contador = 0;

echo "6. Antes del foreach<br>";

foreach ($productos_externos as $prod) {
    echo "Procesando producto...<br>";
    var_dump($prod);
    echo "<br>";
    
    $id_code = mysqli_real_escape_string($conn, $prod['id']);
    $name = mysqli_real_escape_string($conn, $prod['name']);
    $price = mysqli_real_escape_string($conn, $prod['price']);
    
    echo "ID_code: $id_code, Name: $name, Price: $price<br>";
    $check_sql = "SELECT id FROM 025_products WHERE id_code = '$id_code' LIMIT 1";
    $result = mysqli_query($conn, $check_sql);
    if(mysqli_num_rows($result) > 0) {
        $sql_update = "UPDATE 025_products 
                       SET name = '$name', price = '$price' 
                       WHERE id_code = '$id_code'";
        mysqli_query($conn, $sql_update);
        echo "Producto actualizado: $id_code<br>";
        continue;
    }
    $sql = "INSERT INTO 025_products (id_code, name, price, stock, category_id, supplier_id) 
            VALUES ('$id_code', '$name', '$price', 0, 1, 3)";
    
    echo "SQL: $sql<br>";
    
    if(mysqli_query($conn, $sql)) {
        $contador++;
        echo "Insertado OK<br>";
    } else {
        echo "Error SQL: " . mysqli_error($conn) . "<br>";
    }
}

echo "Éxito: Se han importado $contador productos.";

mysqli_close($conn);
?>