<?php
function enviar_pedidos_julen(array $items, string $email, string $address, $conn) {
    $resultados = [];
    
    foreach ($items as $i) {
        // Inicializar cURL
        $url = "https://remotehost.es/student008/shop/backend/api/recive_orders.php";
        
        $ch = curl_init($url);
        
        // Preparar datos
        $postData = [
            'api_key'  => "3333",
            'id_code'  => $i['id_code'],
            'email'    => $email,
            'address'  => $address,
            'quantity' => $i['quantity']
        ];
        
        // Configurar cURL
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => http_build_query($postData),  // ✅ Usar http_build_query
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_FOLLOWLOCATION => true,  // ✅ Seguir redirecciones
            CURLOPT_HTTPHEADER     => [      // ✅ Añadir headers
                'Content-Type: application/x-www-form-urlencoded',
                'User-Agent: PHP-cURL'
            ]
        ]);
        
        // Ejecutar
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_error = curl_error($ch);
        
        // ✅ IMPORTANTE: Cerrar handle
        curl_close($ch);
        
        // Log detallado
        error_log("=== PEDIDO A JULEN (student008) ===");
        error_log("ID Code enviado: " . $i['id_code']);
        error_log("HTTP Code: " . $http_code);
        error_log("Respuesta: " . $response);
        error_log("Error cURL: " . ($curl_error ?: 'Ninguno'));
        error_log("===================================");
        
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