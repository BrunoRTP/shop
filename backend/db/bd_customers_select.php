<?php
$root_dir = $_SERVER['DOCUMENT_ROOT'] . '/student025/shop/backend/';
include($root_dir. 'header.php'); 
include($root_dir . 'db_connection.php'); 
?>

<?php
    if(isset($_POST['select_user'])){
        $user_id = $_POST['user_id'];
        $sql = "SELECT * FROM 025_customers";

        $result = mysqli_query($conn, $sql);
            
        while ($row = mysqli_fetch_assoc($result)) {
            echo "ID: " . $row['user_id'] . ", Nombre: " . $row['user_name'] . ", Email: " . $row['user_email'] . ", Dirección: " . $row['user_address'] . ", Fecha Registro: " . $row['user_register_date'] . "<br>";
        }
    }
    mysqli_close($conn);
?>
<?php include($root_dir . 'footer.php'); ?>
