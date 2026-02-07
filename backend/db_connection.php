<?php
    $hostName = htmlspecialchars($_SERVER['SERVER_NAME']);
    // echo "Conectando a la base de datos desde host: $hostName\n";
    
    switch ($hostName) {
        case 'localhost':
            $user = 'root';
            $password = '';
            $dbName = 'shop';
            break;
        case 'remotehost.es':
            $user = 'dwess1234';
            $password = 'Usertest1234.';
            $dbName = 'dwesdatabase';
            break;
        default:
            $hostName='remotehost.es';
            $user = 'dwess1234';
            $password = 'Usertest1234.';
            $dbName = 'dwesdatabase';
            break;
    }

    $conn = mysqli_connect($hostName, $user, $password, $dbName);
    if(!$conn){
        echo "Connection error: " . mysqli_connect_error();
        exit();
    }

    mysqli_set_charset($conn, "utf8");
    mysqli_query($conn, "SET time_zone = '+01:00'");
    // Comprobar horario de verano Bruno

?>