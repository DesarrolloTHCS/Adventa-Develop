<?php
namespace App\Models;

use  App\Models\GetModel;
use PDO;
use PDOException;

class Connection
{
  
  /*
  Información de la base de datos.
  */
  static public function infoDataBase()
  {
    $infoDB = array(
      "database" => "thcsnativo",
      "user" => 'root',
      "password" => ""
    );
    return $infoDB;
  }

  static public function connect()
  {
    try {
      $db = new PDO(
        "mysql:host=localhost;dbname=" . Connection::infoDataBase()["database"],
        Connection::infoDataBase()['user'],
        Connection::infoDataBase()['password'],
      );
      $db->exec("set names utf8");
    } catch (PDOException $error) {
      die("Error:" . $error->getMessage());
    }
    return $db;
  }
  /*Validar existencia de las tablas y columnas*/
  static public function getColumnsData($table, $colums)
  {
    $db = Connection::infoDataBase()["database"];
    $validate = Connection::connect()
      ->query("SELECT COLUMN_NAME AS item FROM information_schema.columns WHERE table_schema = '$db' AND table_name='$table'")
      ->fetchAll(PDO::FETCH_OBJ);
    /*Validar existencia de las tablas*/
    if (empty($validate)) {
      return null;
    } else {
      /*Validar existencia de todas las columnas*/
      if ($colums[0] == "*") {
        array_shift($colums);
      }
      $sum = 0;
      foreach ($validate as $key => $value) {
        $sum += in_array($value->item, $colums);
      }
      /*Validar existencia de las columnas solicitadas*/
      return $sum == count($colums) ? $validate : null;
    }
  }
  /*Generar token de autenticacion */
  static public function jWToken($id, $email)
  {
    $time = time();
    $token = array(
      "iat" => $time, //Timepo de inicio del token
      "exp" => $time + (60 * 60 * 24), //Timepo de expiracion del token
      "data" => [
        "id" => $id,
        "email" => $email
      ]
    );
    return $token;
  }

  /*Validación de token de seguridad */
  static public function tokenValidate($table,  $token, $suffix)
  {
    $user = GetModel::getDataFilter($table, "token_exp_" . $suffix, "token_" . $suffix, $token, null, null, null, null);
    $time = time();

    if (!empty($user)) {
      if ($user[0]->{"token_exp_" . $suffix} > $time) {
        return "Ok";
      } else {
        return "Expired";
      }
    } else {
      return "no-Auth";
    }
  }
  /*ApiKey administrador */
  static public function apiKey()
  {
    return 'Et#hV&cYkyVTe%ppMa8BhqtDeHC$wo';
  }

  /*Acceso publico */
  static public function publicAcces()
  {
    $table = ["courses"];
    return $table;
  }
}
