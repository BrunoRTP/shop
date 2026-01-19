<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$root_dir = $_SERVER['DOCUMENT_ROOT'] . '/student025/shop/backend/';
include($root_dir . 'db_connection.php');

// Obtener el clima actual (más reciente)
$sql_current = "SELECT * FROM 025_weather_history 
                ORDER BY recorded_at DESC 
                LIMIT 1";
$result = mysqli_query($conn, $sql_current);
$current_row = mysqli_fetch_assoc($result);

if (!$current_row) {
    echo json_encode(['success' => false, 'message' => 'No hay datos del clima']);
    mysqli_close($conn);
    exit;
}

// Decodificar el JSON guardado
$current_weather = json_decode($current_row['json_data'], true);

// Obtener historial de los últimos 3 días (1 registro por día)
$sql_history = "SELECT 
                    DATE(recorded_at) as date,
                    recorded_at,
                    json_data
                FROM 025_weather_history
                WHERE recorded_at >= DATE_SUB(NOW(), INTERVAL 3 DAY)
                GROUP BY DATE(recorded_at)
                ORDER BY date DESC
                LIMIT 3";

$result_history = mysqli_query($conn, $sql_history);
$history = [];

while ($row = mysqli_fetch_assoc($result_history)) {
    $weather_data = json_decode($row['json_data'], true);
    
    $history[] = [
        'date' => $row['date'],
        'time' => date('H:i', strtotime($row['recorded_at'])),
        'temperature' => round($weather_data['Temperature']['Metric']['Value'], 1),
        'wind_speed' => round($weather_data['Wind']['Speed']['Metric']['Value'], 1),
        'weather_text' => $weather_data['WeatherText'],
        'icon' => $weather_data['WeatherIcon']
    ];
}

mysqli_close($conn);

echo json_encode([
    'success' => true,
    'current' => [
        'temperature' => round($current_weather['Temperature']['Metric']['Value'], 1),
        'weather_text' => $current_weather['WeatherText'],
        'wind_speed' => round($current_weather['Wind']['Speed']['Metric']['Value'], 1),
        'humidity' => $current_weather['RelativeHumidity'],
        'icon' => $current_weather['WeatherIcon'],
        'recorded_at' => date('d/m/Y H:i', strtotime($current_row['recorded_at']))
    ],
    'history' => $history
]);
?>