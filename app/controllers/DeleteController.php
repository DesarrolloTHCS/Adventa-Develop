<?php
namespace App\Controllers;
use App\Models\DeleteModel;
class DeleteController
{
  /*Peticion DELETE para eliminar datos*/
  static public function deleteData($table, $id, $nameId)
  {
    $response = DeleteModel::deleteData($table, $id, $nameId);
    $result = new DeleteController();
    return $result->response($response);
  }

  /*Crea las respuestas de tipo JSON*/
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
        'result' => "Error al eliminar datos"

      );
      echo json_encode($json, http_response_code($json["status"]));
    }
  }
}
