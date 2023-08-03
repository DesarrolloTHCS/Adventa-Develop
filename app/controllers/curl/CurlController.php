<?php
namespace App\Controllers\Curl;

class CurlController
{
    static function getCurl($url, $headers = [], $options = [])
    {
        // Inicializar cURL
        $ch = curl_init();

        // Configurar la URL a la que se enviará la solicitud
        curl_setopt($ch, CURLOPT_URL, $url);

        // Configurar encabezados si se proporcionan
        if (!empty($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        // Configurar otras opciones según sea necesario
        if (!empty($options)) {
            curl_setopt_array($ch, $options);
        }

        // Configurar CURLOPT_RETURNTRANSFER para que cURL devuelva el resultado en lugar de imprimirlo
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Ejecutar la solicitud cURL y guardar la respuesta en una variable
        $response = curl_exec($ch);

        // Verificar si hay errores
        if (curl_errno($ch)) {
            echo 'Error en la solicitud cURL: ' . curl_error($ch);
        }

        // Cerrar la sesión cURL
        curl_close($ch);

        return $response;
    }
}
?>