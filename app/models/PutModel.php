<?php

namespace App\Models;

use App\Models\Connection;
use App\Models\GetModel;
use PDO;
use PDOException;

class PutModel
{
  static public function putData($table, $data, $linkTo, $equalTo)
  {
    $linkToArray = explode(',', $linkTo);
    $equalToArray = explode(',', $equalTo);
    $linkToText = "";
    $set = "";


    foreach ($linkToArray as $key) {
      foreach ($equalToArray as $equal) {
        $validate = GetModel::getDataFilter($table, '*', $key, $equal, null, null, null, null);
      }
    }
    if (empty($validate)) {
      return null;
    }


    foreach ($data as $key => $value) {
      $set .= $key . " = " . ":" . $key . ",";
    }

    

    if (count($linkToArray) > 1) {
      foreach ($linkToArray as $key) {
        if ($key > 0) {
          $linkToText .= "AND " . $key . " = :" . $key . " ";
        }
      }
    }
    
    $set = substr($set, 0, -1);
    $sql = "UPDATE $table SET $set WHERE $linkToArray[0]=:$linkToArray[0] $linkToText";

    $conn = Connection::connect();
    $stm = $conn->prepare($sql);
    foreach ($data as $key => $value) {
      $stm->bindParam(":" . $key, $data[$key], PDO::PARAM_STR);
    }

    foreach ($linkToArray as $key=>$value) {
        
        $stm->bindParam(":" . $value, $equalToArray[$key], PDO::PARAM_STR);
    }

    try {
      $stm->execute();
      $response = array(
        "comment" => "Datos actualizados de forma correcta",
      );
      return $response;
    } catch (PDOException $error) {
      return $error;
    }
  }
}
