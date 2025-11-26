<?php 
    $root_dir = $_SERVER['DOCUMENT_ROOT'] . '/student025/shop/backend/';
    include($root_dir . 'header.php'); 
    include($root_dir . 'db_connection.php'); 
?>
<div class="login">
    <div>
        <h2>Login</h2>
        <div class="logForm">
            <form method="POST" action="/student025/shop/backend/db/bd_login.php">
                <label>Usuario:</label><br>
                <input type="text" name="username" required><br><br>
                <label>Contraseña:</label><br>
                <input type="password" name="password" required><br><br>
                <button type="submit" name="btnLog">Iniciar Sesión</button><br>
            </form>
        </div>
    </div>
</div>

<?php 
    mysqli_close($conn);
?>
<?php include($root_dir . 'footer.php'); ?>