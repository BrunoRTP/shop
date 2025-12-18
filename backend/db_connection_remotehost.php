<?php
    $hostName = $_SERVER['SERVER_NAME'];
    echo "Conectando a la base de datos desde host: $hostName\n";
    
    $user = '';
    $password = '';
    $dbName = 'shop';
    $conn = mysqli_connect('remotehost.es', 'dwess1234', 'Usertest1234.', 'dwesdatabase');
    if(!$conn){
        echo "Connection error: " . mysqli_connect_error();
        exit();
    }
    mysqli_set_charset($conn, "utf8");
    mysqli_query($conn, "SET time_zone = '+01:00'");
?>