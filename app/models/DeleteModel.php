<?php
namespace App\Models;

use App\Models\Connection;
use App\Models\GetModel;
use PDO;
use PDOException;

/* 
require_once '/shop_thcs_nativo/app/models/Connection.php';
require_once '/shop_thcs_nativo/app/models/GetModel.php'; */

class DeleteModel
{
  static public function deleteData($table, $id, $nameId)
  {
    $validate=GetModel::getDataFilter($table,$nameId,$nameId,$id,null,null,null,null);
    if(empty($validate)){
      return null;
    }    
    $sql = "DELETE FROM $table WHERE $nameId=:$nameId";
    $conn = Connection::connect();
    $stm = $conn->prepare($sql);
    $stm->bindParam(":" . $nameId, $id, PDO::PARAM_STR);
    try {
      $stm->execute();
      $response = array(  
        "comment" => "Datos eliminados de forma correcta",
      );
      return $response;
    } catch (PDOException $error) {
      return null;
    }
  }
}
