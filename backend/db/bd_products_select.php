<?php
$root_dir = $_SERVER['DOCUMENT_ROOT'] . '/student025/shop/backend/';
include($root_dir. 'header.php'); 
include($root_dir . 'db_connection.php'); 
?>

<?php
    if(isset($_POST['select_product'])){
        $id = $_POST['id'];
        $sql = "SELECT * FROM 025_products";

        $result = mysqli_query($conn, $sql);
            
        while ($row = mysqli_fetch_assoc($result)) {
            echo "ID: " . $row['id'] . ", Nombre: " . $row['name'] . ", Descripcion: " . $row['description'] . ", Cantidad: " . $row['quantity_available'] . ", Precio: " . $row['price'] . "<br>";
        }
    }
?>
<?php include($root_dir . 'footer.php'); ?>