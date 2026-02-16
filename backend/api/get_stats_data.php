<?php
// API para obtener datos de estadísticas
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$root_dir = $_SERVER['DOCUMENT_ROOT'] . '/student025/shop/backend/';
include($root_dir . 'db_connection.php');

$type = $_GET['type'] ?? 'all';

$response = [];

try {
    switch($type) {
        case 'monthly':
            // Datos para gráfico de barras - Ventas por mes
            $sql = "SELECT * FROM v_orders_by_month LIMIT 12";
            $result = mysqli_query($conn, $sql);
            $data = [];
            while($row = mysqli_fetch_assoc($result)) {
                $data[] = [
                    'month' => $row['month_name'],
                    'orders' => (int)$row['total_orders'],
                    'revenue' => (float)$row['total_revenue'],
                    'quantity' => (int)$row['total_quantity']
                ];
            }
            $response = $data;
            break;
            
        case 'product':
            // Datos para gráfico pie - Ventas por producto
            $sql = "SELECT * FROM v_orders_by_product LIMIT 10";
            $result = mysqli_query($conn, $sql);
            $data = [];
            while($row = mysqli_fetch_assoc($result)) {
                $data[] = [
                    'product' => $row['product_name'],
                    'revenue' => (float)$row['total_revenue'],
                    'quantity' => (int)$row['total_quantity_sold'],
                    'orders' => (int)$row['times_ordered']
                ];
            }
            $response = $data;
            break;
            
        case 'trend':
            // Datos para gráfico de líneas - Tendencia últimos 30 días
            $sql = "SELECT * FROM v_sales_trend";
            $result = mysqli_query($conn, $sql);
            $data = [];
            while($row = mysqli_fetch_assoc($result)) {
                $data[] = [
                    'date' => $row['day_label'],
                    'orders' => (int)$row['orders_count'],
                    'revenue' => (float)$row['daily_revenue']
                ];
            }
            $response = $data;
            break;
            
        case 'customer':
            // Datos adicionales por cliente
            $sql = "SELECT * FROM v_orders_by_customer LIMIT 10";
            $result = mysqli_query($conn, $sql);
            $data = [];
            while($row = mysqli_fetch_assoc($result)) {
                $data[] = [
                    'customer' => $row['username'],
                    'email' => $row['email'],
                    'orders' => (int)$row['total_orders'],
                    'spent' => (float)$row['total_spent']
                ];
            }
            $response = $data;
            break;
            
        default:
            // Devolver todos los datos
            $response['monthly'] = [];
            $response['product'] = [];
            $response['trend'] = [];
            
            // Monthly data
            $sql = "SELECT * FROM v_orders_by_month LIMIT 12";
            $result = mysqli_query($conn, $sql);
            while($row = mysqli_fetch_assoc($result)) {
                $response['monthly'][] = [
                    'month' => $row['month_name'],
                    'orders' => (int)$row['total_orders'],
                    'revenue' => (float)$row['total_revenue']
                ];
            }
            
            // Product data
            $sql = "SELECT * FROM v_orders_by_product LIMIT 10";
            $result = mysqli_query($conn, $sql);
            while($row = mysqli_fetch_assoc($result)) {
                $response['product'][] = [
                    'product' => $row['product_name'],
                    'revenue' => (float)$row['total_revenue']
                ];
            }
            
            // Trend data
            $sql = "SELECT * FROM v_sales_trend";
            $result = mysqli_query($conn, $sql);
            while($row = mysqli_fetch_assoc($result)) {
                $response['trend'][] = [
                    'date' => $row['day_label'],
                    'revenue' => (float)$row['daily_revenue']
                ];
            }
            break;
    }
    
    echo json_encode($response);
    
} catch(Exception $e) {
    echo json_encode([
        'error' => true,
        'message' => $e->getMessage()
    ]);
}

mysqli_close($conn);
?>