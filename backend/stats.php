<?php 
    $root_dir = $_SERVER['DOCUMENT_ROOT'] . '/student025/shop/backend/';
    include($root_dir . 'auth_functions.php');
    require_login();
    
    // Solo admins pueden ver estadísticas
    if($_SESSION['type_client'] != 'admin'){
        header("Location: /student025/shop/backend/index.php");
        exit;
    }
    
    include($root_dir . 'header.php'); 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estadísticas de Ventas</title>

    <script src="https://cdn.plot.ly/plotly-2.27.0.min.js"></script>

</head>
<body>
    <div class="stats-container">
        <a href="/student025/shop/backend/products.php" class="back-button">← Volver a Productos</a>
        
        <!-- Gráfico 1: Ventas por Mes (Barras) -->
        <div class="chart-container">
            <div class="chart-title">Ventas Mensuales</div>
            <div class="chart-wrapper" id="chart-monthly">
                <div class="loading">Cargando datos...</div>
            </div>
        </div>
        
        <!-- Gráfico 2: Distribución de Ventas por Producto (Pie) -->
        <div class="chart-container">
            <div class="chart-title">Distribución de Ingresos por Producto</div>
            <div class="chart-wrapper" id="chart-product">
                <div class="loading">Cargando datos...</div>
            </div>
        </div>
        
        <!-- Gráfico 3: Tendencia de Ventas Diarias (Líneas) -->
        <div class="chart-container">
            <div class="chart-title">Tendencia de Ventas (Últimos 30 días)</div>
            <div class="chart-wrapper" id="chart-trend">
                <div class="loading">Cargando datos...</div>
            </div>
        </div>
    </div>
    
<script src="/student025/shop/js/stats.js"></script>
</body>
</html>

<?php include($root_dir . 'footer.php'); ?>