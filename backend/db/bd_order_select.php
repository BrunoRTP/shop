<?php
$root_dir = $_SERVER['DOCUMENT_ROOT'] . '/student025/shop/backend/';
include($root_dir. 'header.php'); 
include($root_dir . 'db_connection.php'); 
?>

<?php
    if(isset($_POST['select_order'])){
        $id = $_POST['id'];
        $sql = "SELECT o.*, c.username, p.name as product_name 
                FROM 025_order o
                LEFT JOIN 025_customers c ON o.customer_id = c.id
                LEFT JOIN 025_products p ON o.product_id = p.id";

        $result = mysqli_query($conn, $sql);
            
        while ($row = mysqli_fetch_assoc($result)) {
            echo "ID: " . $row['id'] . ", Cliente: " . $row['username'] . ", Producto: " . $row['product_name'] . ", Cantidad: " . $row['quantity'] . ", Precio: " . $row['price'] . "<br>";
        }
    }
    mysqli_close($conn);
?>
<?php include($root_dir . 'footer.php'); ?>