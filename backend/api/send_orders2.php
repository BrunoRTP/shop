<?php
    function enviar_pedidos_julen(array $items, string $email, string $address, $conn) {


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
                CURLOPT_SSL_VERIFYPEER => false  // Solo necesario en local con WAMP
            ]);
            $response = curl_exec($ch);

            // DEBUG TEMPORAL - quitar después
            error_log("URL: " . "https://remotehost.es/student008/shop/backend/api/recive_orders.php");
            error_log("Respuesta: " . $response);
            error_log("Error cURL: " . curl_error($ch));
        }
    }
?>