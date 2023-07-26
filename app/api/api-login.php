<?php

use App\Models\GetModel;
use App\Traits\Validate;
use App\Controllers\ShoppingCart\ShoppingCart;
use App\Controllers\Wishlist\WishlistController;

require_once ($_SERVER['DOCUMENT_ROOT'].'/Adventa-Develop/vendor/autoload.php');

// Lee el contenido de la solicitud JSON
   $requestPayload = file_get_contents('php://input');

   // Decodifica el contenido JSON en un objeto PHP
   $data = json_decode($requestPayload);
   
   //Obtiene el método de la solicitud
   $method = $_SERVER['REQUEST_METHOD'];


if($method=="POST"){
try{
    if(isset($data)){
        $email=Validate::validateEmail($data->singin_email);
        $password=Validate::validatePassword($data->singin_password);

        if(is_array($email)){
          response($email,404);
          exit();
        }
        
        if(is_array($password)){
        response($password,404);
        exit();
      }
        $response=GetModel::getDataFilter('users','id_user,id_company,name_user,password_user','email_user', $email,null,null,null,null);
        if(empty($response)){
          $error = [
            "error"=>"El usuario no existe"
          ];
          response($error,404);
          exit();
        }
        if(password_verify($password,$response[0]->password_user)){        
        
        $id_user=$response[0]->id_user;
        $id_company=$response[0]->id_company;
        $company=GetModel::getDataFilter('companies','*','id_company',$id_company,null,null,null,nulL);

        session_start();
        $_SESSION["id_user"]=$response[0]->id_user;
        $_SESSION["id_company"]=$response[0]->id_company;
        $_SESSION["name_user"]=$response[0]->name_user;
        $response=[
          "id_user"=>$id_user,
          "id_company"=>$id_company,
          "company"=>$company
        ];
        response($response,200);
        header("Location: ../../views/index.php");
        exit();
        
        }else{
            $error = [
              "error"=>"La contraseña es incorrecta"
            ];
              response($error,404);
              exit();
            }

    }else{
      $error = [
        "error"=>"Los campos email y password son obligatorios"
      ];
      response($error,404);
      exit();

    }
}catch(Exception $e){
  $error = array(
    "error" => "El método: $method no es válido, solo se acepta POST"
  );
  response($error,404);
  exit();
}


}else{
  $error = array(
    "Error" => "El método: $method no es válido, solo se acepta POST"
  );
  response($error,404);
  exit();
}
      /*Crea las respuestas de tipo JSON*/
      function response($response,$status=200)
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