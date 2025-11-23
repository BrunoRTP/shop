<?php
    $conn = mysqli_connect('localhost', 'root', '', 'shop');
    if(!$conn){
        echo "Connection error: " . mysqli_connect_error();
        exit();
    }

    mysqli_set_charset($conn, "utf8");
?>