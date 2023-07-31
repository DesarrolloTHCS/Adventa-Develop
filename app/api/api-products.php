<?php
/**
 * Author:Alfredo Segura <pixxo2010@gmail.com>
 * Description: Obtiene los productos  de catalog_products
 * Method: POST
 * Date: 31/07/2023
 */

use App\Controllers\Catalogs\CatalogProductsController;
use App\Traits\Validate;
use App\Traits\Responses;

require_once ($_SERVER['DOCUMENT_ROOT'].'/Adventa-Develop/vendor/autoload.php');

// Lee el contenido de la solicitud JSON
$requestPayload = file_get_contents('php://input');

// Decodifica el contenido JSON en un objeto PHP
$data = json_decode($requestPayload);

//Obtiene el método de la solicitud
$method=$_SERVER['REQUEST_METHOD'];

if($method=="GET"){
    $type=Validate::validateSearch($_GET['type']);
    $id_producut=Validate::validateSearch($_GET['id_product']??null);
    switch ($type) {
        case 'products':
            $result=CatalogProductsController::getProducts();
            Responses::response($result);
            break;
        case 'detail':
            $result=CatalogProductsController::getProductsById($id_producut);
            
            Responses::response($result);
            break;
        default:

            break;
    }

}