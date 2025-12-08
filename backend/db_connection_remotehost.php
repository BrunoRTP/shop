<?php
    $conn = mysqli_connect('remotehost.es', 'dwes1234', 'usertest1234.', 'dwesdatabase');
    if(!$conn){
        echo "Connection error: " . mysqli_connect_error();
        exit();
    }
    mysqli_set_charset($conn, "utf8");
    mysqli_query($conn, "SET time_zone = '+01:00'");
?>