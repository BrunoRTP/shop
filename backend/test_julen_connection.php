<?php
// Test de conexión con student008

header('Content-Type: text/plain; charset=UTF-8');

echo "=== TEST DE CONEXIÓN CON STUDENT008 ===\n\n";

$url = "https://remotehost.es/student008/shop/backend/api/recive_orders.php";

$data = [
    'api_key'  => '3333',
    'id_code'  => '6',
    'email'    => 'test@test.com',
    'address'  => 'Calle Test 123',
    'quantity' => '1'
];

echo "URL: $url\n";
echo "Datos a enviar:\n";
print_r($data);
echo "\n";

// Inicializar cURL
$ch = curl_init($url);

curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => http_build_query($data),
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_TIMEOUT        => 30,
    CURLOPT_CONNECTTIMEOUT => 10,
    CURLOPT_HTTPHEADER     => [
        'Content-Type: application/x-www-form-urlencoded'
    ]
]);

echo "Enviando petición...\n\n";

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curl_error = curl_error($ch);
$curl_info = curl_getinfo($ch);

curl_close($ch);

echo "=== RESULTADO ===\n";
echo "HTTP Code: $http_code\n";
echo "Error cURL: " . ($curl_error ?: 'Ninguno') . "\n";
echo "\nRespuesta del servidor:\n";
echo $response . "\n";

echo "\n=== INFO COMPLETA DE CURL ===\n";
print_r($curl_info);

// Intentar decodificar JSON
if ($http_code == 200) {
    $json = json_decode($response, true);
    if ($json) {
        echo "\n=== JSON DECODIFICADO ===\n";
        print_r($json);
        
        if (isset($json['success']) && $json['success'] === true) {
            echo "\n✅ ÉXITO: La conexión funciona correctamente!\n";
        } else {
            echo "\n❌ ERROR: El servidor respondió pero con error:\n";
            echo $json['message'] ?? 'Sin mensaje de error';
            echo "\n";
        }
    }
} else {
    echo "\n❌ ERROR: HTTP code $http_code\n";
    if ($http_code == 0) {
        echo "Posibles causas:\n";
        echo "- Firewall bloqueando la conexión\n";
        echo "- URL incorrecta\n";
        echo "- Servidor student008 caído\n";
        echo "- Problema de DNS\n";
    }
}
?>