<?php
namespace App\Controllers;

use App\Models\PutModel;

class PutController{
/*Peticion Put para actualizar datos*/
static public function putData($table, $data, $id, $nameId){
  $response=PutModel::putData($table, $data, $id, $nameId);
  $result=new PutController();
  return $result->response($response);
  
}
public function response($response)
  {
    if (!empty($response)) {
      $json = array(
        "status" => 200,
        'result' => $response

      );
      echo json_encode($json, http_response_code($json["status"]));
    } else {
      $json = array(
        "status" => 404,
        'result' => "Error al actualizar datos"

      );
      echo json_encode($json, http_response_code($json["status"]));
    }
  }
}