<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test</title>
    <link rel="stylesheet" href="/student025/shop/css/styleBack.css"> 
</head>
<body>

    <nav class="navbar">
        <div class="logo">
            <a href="/student025/shop/backend/index.php">Mi Marca</a>
        </div>
        <ul class="nav-links">
            <?php    
                echo'<li><a href="/student025/shop/backend/products.php">Products</a></li>';
                echo'<li><a href="/student025/shop/backend/orders.php">Orders</a></li>';
                echo'<li><a href="/student025/shop/backend/customers.php">Customers</a></li>';
                echo '<li><a href="/student025/shop/backend/cart.php">Cart</a></li>';
                echo '<li><a href="/student025/shop/backend/logout.php">Close session</a></li>';
            ?>
        </ul>
    </nav>
