<?php
namespace App\Models;

use App\Models\Connection;
use PDO;
use PDOException;


class PostModel
{
/*Peticio Post para registrar datos*/
  static public function postData($table, $data)
  {
    $columns = "";
    $params = "";    
    foreach ($data as $key => $value) {
      $columns.=$key.",";
      $params.=":".$key.",";
    }
    $columns=substr($columns,0,-1);
    $params=substr($params,0,-1);
    $sql = "INSERT INTO $table($columns) VALUES ($params)";
    $conn = Connection::connect();
    $stm = $conn->prepare($sql);
    foreach ($data as $key =>$value){
      $stm->bindParam(":".$key,$data[$key],PDO::PARAM_STR);
    }
    try{
      $stm->execute();
      $response=array(
        "lastId"=>$conn->lastInsertId(),
        "comment"=>"Datos alamacenados de forma correcta",
      );
      return $response;
    }catch(PDOException $error){
      return $error->getMessage();
    }
  }

}
