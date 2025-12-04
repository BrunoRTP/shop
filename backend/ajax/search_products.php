<?php
header('Content-Type: application/json');

$root_dir = $_SERVER['DOCUMENT_ROOT'] . '/student025/shop/backend/';
include($root_dir . 'db_connection.php');

$search = isset($_GET['q']) ? trim($_GET['q']) : '';
$results = array();

if(strlen($search) > 0) {
    $search = mysqli_real_escape_string($conn, $search);
    
    $sql = "SELECT id, `name`, `description`, stock, price 
            FROM 025_products 
            WHERE name LIKE '%$search%'
            ORDER BY id ASC";
    
    $result = mysqli_query($conn, $sql);
    
    if($result) {
        while($row = mysqli_fetch_assoc($result)) {
            $results[] = array(
                'id' => $row['id'],
                'name' => $row['name'],
                'description' => $row['description'],
                'stock' => $row['stock'],
                'price' => number_format($row['price'], 2)
            );
        }
    }
}

mysqli_close($conn);

echo json_encode($results);
?>