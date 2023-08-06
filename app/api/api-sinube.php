<?php
/**
 * Author:Alfredo Segura <pixxo2010@gmail.com>
 * Description: API a alamacen sinube
 * Method: POST
 * Date: 25/07/2023
 */

use App\Controllers\Sinube\SinubeController;
use App\Traits\Validate;
use App\Traits\Responses;

require_once ($_SERVER['DOCUMENT_ROOT'].'/Adventa-Develop/vendor/autoload.php');

// Lee el contenido de la solicitud JSON
$requestPayload = file_get_contents('php://input');

// Decodifica el contenido JSON en un objeto PHP
$data = json_decode($requestPayload);

//Obtiene el método de la solicitud
$method=$_SERVER['REQUEST_METHOD'];

if($method=="POST"){
$result=SinubeController::consultar();
print_r($result);
Responses::response($result);
}