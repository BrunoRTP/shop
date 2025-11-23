<?php 
    $root_dir = $_SERVER['DOCUMENT_ROOT'] . '/student025/shop/backend/';
    include($root_dir . 'auth_functions.php');
    require_login();
    include($root_dir . 'header.php'); 
    include($root_dir . 'db_connection.php'); 
?>

<div class="container">
    <h2>Carrito de Compras</h2>
    
    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>Customer ID</th>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Subtotal</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $customer_id = $_SESSION['user_id'];
                
                $sql = "SELECT c.*, p.name, p.price 
                        FROM 025_cart c 
                        INNER JOIN 025_products p ON c.product_id = p.id
                        WHERE c.customer_id = $customer_id";
                $result = mysqli_query($conn, $sql);
                
                $total = 0;
                $has_items = false;
                
                while ($row = mysqli_fetch_assoc($result)) {
                    $has_items = true;
                    $product_id = $row['product_id'];
                    $subtotal = $row['quantity'] * $row['price'];
                    $total += $subtotal;
                    
                    echo '<tr>';
                    echo '<td>' . $row['customer_id'] . '</td>';
                    echo '<td>' . $row['name'] . '</td>';
                    echo '<td>' . $row['quantity'] . '</td>';
                    echo '<td>€' . number_format($row['price'], 2) . '</td>';
                    echo '<td>€' . number_format($subtotal, 2) . '</td>';
                    echo '<td>';
                    
                    echo '<a href="/student025/shop/backend/db/db_cart_insert.php?id=' . $product_id . '">';
                    echo '<button type="button">Añadir</button>';
                    echo '</a> ';
                    
                    echo '<a href="/student025/shop/backend/db/db_cart_delete.php?id=' . $product_id . '">';
                    echo '<button type="button">Eliminar</button>';
                    echo '</a>';
                    
                    echo '</td>';
                    echo '</tr>';
                }
                
                if(!$has_items){
                    echo '<tr><td colspan="6">Tu carrito está vacío</td></tr>';
                }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4"><strong>Total:</strong></td>
                <td><strong>€<?= number_format($total, 2) ?></strong></td>
                <td></td>
            </tr>
        </tfoot>
    </table>
    
    <br>
    <button>
        <a href="/student025/shop/backend/products.php" class="social-icon">Volver a Productos</a>
    </button>
    
    <?php if($has_items): ?>
    <button onclick="if(confirm('¿Confirmar pedido por €<?= number_format($total, 2) ?>?')) window.location.href='/student025/shop/backend/db/db_cart_checkout.php'">
        Realizar Pedido
    </button>
    <?php endif; ?>
</div>

<?php 
    mysqli_close($conn);
    include($root_dir . 'footer.php'); 
?>