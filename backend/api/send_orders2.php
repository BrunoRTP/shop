<?php
function enviar_pedidos_julen(array $items, string $email, string $address, $conn) {
    $resultados = []; // Para guardar los resultados
    
    foreach ($items as $i) {
        $ch = curl_init("https://remotehost.es/student008/shop/backend/api/recive_orders.php");
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => [
                'api_key'  => "3333",
                'id_code'  => $i['id_code'],
                'email'    => $email,
                'address'  => $address,
                'quantity' => $i['quantity']
            ],
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CONNECTTIMEOUT => 10
        ]);
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_error = curl_error($ch);
        
        // Guardar resultado
        $resultados[] = [
            'id_code' => $i['id_code'],
            'http_code' => $http_code,
            'response' => $response,
            'error' => $curl_error
        ];
        
        // DEBUG - Log completo
        error_log("=== PEDIDO EXTERNO ===");
        error_log("ID Code: " . $i['id_code']);
        error_log("HTTP Code: " . $http_code);
        error_log("Respuesta: " . $response);
        error_log("Error cURL: " . $curl_error);
        error_log("=====================");
        
        curl_close($ch);
    }
    
    return $resultados; // Retornar los resultados
}
?>