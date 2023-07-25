<?php 
/*
*Author: Alfredo Segura Vara <pixxo2010@gmaiL.com>
*Description: API para obtener los datos del usuario
*Method: GET
*date: 15/06/2023
*/
use App\Models\GetModel;
require_once ($_SERVER['DOCUMENT_ROOT'].'/Adventa-Develop/vendor/autoload.php');
session_start();

if (isset($_SESSION['id_user'])) {
    // Lee el contenido de la solicitud JSON
    $requestPayload = file_get_contents('php://input');

    // Decodifica el contenido JSON en un objeto PHP
    $data = json_decode($requestPayload);
    
    //Obtiene el método de la solicitud
    $method = $_SERVER['REQUEST_METHOD'];

    //Valida el método de la solicitud
    if ($method == "GET") {
        $id_user = $_SESSION['id_user'];
        $result = GetModel::getDataFilter('users',
        '*',
        'id_user', $id_user,null,null,null,null); //Obtiene los datos del usuario
        
        if (empty($result)) {
            $error = array(
                "error" => "No se encontraron datos"
            );
            response($error, 404);
            exit();
        }

        $data=[
            'register_name'=>$result[0]->name_user,
            'register_paternal'=>$result[0]->paternal_last_name_user,
            'register_maternal'=>$result[0]->maternal_last_name_user,
            'register_rfc'=>$result[0]->rfc_user,
            'register_email'=>$result[0]->email_user,
            'register_sex'=>$result[0]->sex_user,
            'register_phone'=>$result[0]->office_telephone_user,
            'register_cellphone'=>$result[0]->celular_telephone_user,
            'register_username'=>$result[0]->username,
        ];//Crea un arreglo con los datos del usuario

        response($data);
        exit();

    }else{
        $error = array(
            "error" => "Recibe los datos por GET"
        );
        response($error, 404);
        exit();
    }
    
}else {
    $error = array(
        "error" => "Se requiere autorización"
    );
    response($error, 404);
    exit();
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

?>