<?php
namespace App\Controllers\Auth;

use App\Controllers\GetController;

class LoginController{

    static function login($email, $password){
     GetController::getDataFilter('users','id_user,name_user','email_user,password', $email.",".$password,'id', 'ASC',0,100);
    
    }
      /*Crea las respuestas de tipo JSON*/
  public function response($response)
  {
    if (!empty($response)) {
      $json = array(
        "status" => 200,
        "total" => count($response),
        'result' => $response

      );
      echo json_encode($json, http_response_code($json["status"]));
    } else {
      $json = array(
        "status" => 404,
        'result' => "No found"

      );
      echo json_encode($json, http_response_code($json["status"]));
    }
  }
}