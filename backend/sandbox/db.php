<?php
    $root_dir = $_SERVER['DOCUMENT_ROOT'] . '/student025/shop/backend/';
    include($root_dir . 'db_connection.php'); 
?>

<?php

    $text = $_GET["text"];
    $sql = "SELECT `name` FROM 025_products WHERE `name` LIKE '%$text%'";
    $result = mysqli_query($conn, $sql);
    $products = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $products_json = json_encode($products);
    mysqli_close($conn);

?>
