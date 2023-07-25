<?php 
/*
*Author: Alfredo Segura Vara <pixxo2010@gmaiL.com>
*Description: API para buscar productos
*Method: GET
*date: 15/06/2023
*/
use App\Models\GetModel;
use App\Traits\Validate;
require_once ($_SERVER['DOCUMENT_ROOT'].'/Adventa-Develop/vendor/autoload.php');

    // Lee el contenido de la solicitud JSON
    $requestPayload = file_get_contents('php://input');

    // Decodifica el contenido JSON en un objeto PHP
    $data = json_decode($requestPayload);
    
    //Obtiene el método de la solicitud
    $method = $_SERVER['REQUEST_METHOD'];

if ($method == "POST") {
    $search=Validate::validateSearch($data->search);
    $result=GetModel::getDataSearchAll ('catalog_products','*','product_brand,product_name,product_description',$search,null,null,0,5); //Obtiene los datos del usuario)    
    if (empty($result)) {
        $result=[
            "error"=>"No se encontraron resultados"
        ];
        response($result, 404);
        exit();
    }
    echo response($result); //Envía la respuesta al cliente
    exit(); //Termina la ejecución del script
}else{
    $result=[
        "error"=>"Método no permitido"
    ];
    response($result, 404);
}

    /*Crea las respuestas de tipo JSON*/
function response($response, $status = 200)
{
    if (!empty($response)) {
        $json = array(
            "status" => $status,
            "total" => count($response),
            'result' => $response

        );
        echo json_encode($json, http_response_code($json["status"]));
    } else {
        $json = array(
            //404 Not found
            "status" => $status,
            'result' => $response

        );
        echo json_encode($json, http_response_code($json["status"]));
    }
}