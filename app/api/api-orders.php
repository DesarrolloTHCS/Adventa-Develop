<?php
/**
 * Author:Alfredo Segura <pixxo2010@gmail.com>
 * Description: Recibe las ordenes de compra y genera una nota de venta
 * Method: POST
 * Date: 03/08/2023
 */

use App\Controllers\Orders\OrdersController;
use App\Traits\Validate;
use App\Traits\Responses;

require_once ($_SERVER['DOCUMENT_ROOT'].'/Adventa-Develop/vendor/autoload.php');

// Lee el contenido de la solicitud JSON
$requestPayload = file_get_contents('php://input');

// Decodifica el contenido JSON en un objeto PHP
$data = json_decode($requestPayload);

//Obtiene el método de la solicitud
$method=$_SERVER['REQUEST_METHOD'];

$type_order=$_GET['type'];

if($method=="POST"){
    switch ($type_order){
        case "order":
            $result=OrdersController::preOrder($data);
            break;
    }
}