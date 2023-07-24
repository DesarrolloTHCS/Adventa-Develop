<?php
namespace App\Controllers;

use App\Models\GetModel;

class GetController
{
  /*Obtiene los datos de una tabla sin filtrar*/
  static public function getData($table, $select, $orderBy, $orderMode, $startAt, $endAt)
  {
    $response = GetModel::getData($table, $select, $orderBy, $orderMode, $startAt, $endAt);
    $result = new GetController();
    return $result->response($response);
  }

  /*Obtiene los datos de una tabla con filtro*/
  static public function getDataFilter($table, $select, $linkTo, $equalTo, $orderBy, $orderMode, $startAt, $endAt)
  {
    $response = GetModel::getDataFilter($table, $select, $linkTo, $equalTo, $orderBy, $orderMode, $startAt, $endAt);
    $result = new GetController();
    return $result->response($response);
  }

  /*Obtiene los datos de una tabla con relacion sin filtro*/
  static public function getRelData($rel, $type, $select, $orderBy, $orderMode, $startAt, $endAt)
  {
    $response = GetModel::getRelData($rel, $type, $select, $orderBy, $orderMode, $startAt, $endAt);
    $result = new GetController();
    return $result->response($response);
  }

  /*Obtiene los datos de una tabla con relacion con filtro*/
  static public function getRelDataFilter($rel, $type, $select, $linkTo, $equalTo, $orderBy, $orderMode, $startAt, $endAt)
  {
    $response = GetModel::getRelDataFilter($rel, $type, $select, $linkTo, $equalTo, $orderBy, $orderMode, $startAt, $endAt);
    $result = new GetController();
    return $result->response($response);
  }

  /*Busca los datos en una tabla*/
  static public function getDataSearch($table, $select, $linkTo, $search, $orderBy,  $orderMode, $startAt, $endAt)
  {
    $response = GetModel::getDataSearch($table, $select, $linkTo, $search,  $orderBy, $orderMode, $startAt, $endAt);
    $result = new GetController();
    return $result->response($response);
  }

  /*Busca los datos en una tabla con relaciones*/
  static public function getRelDataSearch($rel, $type, $select, $linkTo, $search, $orderBy, $orderMode, $startAt, $endAt)
  {
    $response = GetModel::getRelDataSearch($rel, $type, $select, $linkTo, $search, $orderBy, $orderMode, $startAt, $endAt);
    $result = new GetController();
    return $result->response($response);
  }

  /*Obtinene los datos de una tabla con un rango*/
  static public function getDataRange($table, $select, $linkTo, $betweenMin, $betweenMax, $orderBy, $orderMode, $startAt, $endAt, $filterTo, $inTo)
  {
    $response = GetModel::getDataRange($table, $select, $linkTo, $betweenMin, $betweenMax, $orderBy, $orderMode, $startAt, $endAt, $filterTo, $inTo);
    $result = new GetController();
    return $result->response($response);
  }

  /*Obtinene los datos de una tabla con un rango y relacionados*/
  static public function getRelDataRange($rel, $type, $select, $linkTo, $betweenMin, $betweenMax, $orderBy, $orderMode, $startAt, $endAt, $filterTo, $inTo)
  {
    $response = GetModel::getRelDataRange($rel, $type, $select, $linkTo, $betweenMin, $betweenMax, $orderBy, $orderMode, $startAt, $endAt, $filterTo, $inTo);
    $result = new GetController();
    return $result->response($response);
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
