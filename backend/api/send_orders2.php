<?php
function enviar_pedidos_julen(array $items, string $email, string $address, $conn) {
    $resultados = [];
    
    foreach ($items as $i) {
        $url = "https://remotehost.es/student008/shop/backend/api/recive_orders.php";
        
        // Preparar datos igual que el test exitoso
        $postData = [
            'api_key'  => "3333",
            'id_code'  => $i['id_code'],
            'email'    => $email,
            'address'  => $address,
            'quantity' => $i['quantity']
        ];
        
        // Inicializar cURL
        $ch = curl_init($url);
        
        // Configuración exacta del test exitoso
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => http_build_query($postData), 
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/x-www-form-urlencoded'
            ]
        ]);
        
        // Ejecutar petición
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_error = curl_error($ch);
        
        // ✅ IMPORTANTE: Cerrar handle
        curl_close($ch);
        
        // Log detallado
        error_log("=== PEDIDO A STUDENT008 ===");
        error_log("URL: $url");
        error_log("ID Code: " . $i['id_code']);
        error_log("HTTP Code: $http_code");
        error_log("Respuesta: " . $response);
        error_log("Error cURL: " . ($curl_error ?: 'Ninguno'));
        error_log("===========================");
        
        // Guardar resultado
        $resultados[] = [
            'id_code' => $i['id_code'],
            'http_code' => $http_code,
            'success' => ($http_code == 200),
            'response' => $response,
            'error' => $curl_error
        ];
    }
    
    return $resultados;
}
?>