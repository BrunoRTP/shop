<?php
header('Content-Type: application/json');

$root_dir = $_SERVER['DOCUMENT_ROOT'] . '/student025/shop/backend/';
include($root_dir . 'db_connection.php');

$search = isset($_GET['q']) ? trim($_GET['q']) : '';
$results = array();

if(strlen($search) > 0) {
    $search = mysqli_real_escape_string($conn, $search);
    
    $sql = "SELECT r.id, r.score, r.review, r.created_at,
                   c.username, 
                   p.name as product_name 
            FROM 025_reviews r
            LEFT JOIN 025_customers c ON r.customer_id = c.id
            LEFT JOIN 025_products p ON r.product_id = p.id
            WHERE c.username LIKE '%$search%'
               OR p.name LIKE '%$search%'
               OR r.review LIKE '%$search%'
            ORDER BY r.created_at DESC";
    
    $result = mysqli_query($conn, $sql);
    
    if($result) {
        while($row = mysqli_fetch_assoc($result)) {
            $results[] = array(
                'id' => $row['id'],
                'username' => $row['username'],
                'product_name' => $row['product_name'],
                'score' => $row['score'],
                'review' => $row['review'],
                'created_at' => date('d/m/Y H:i', strtotime($row['created_at']))
            );
        }
    }
}

mysqli_close($conn);

echo json_encode($results);
?>