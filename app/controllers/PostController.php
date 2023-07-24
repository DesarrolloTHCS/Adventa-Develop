<?php

namespace App\Controllers;

use App\Models\GetModel;
use App\Models\PostModel;
use App\Models\PutModel;
use App\Models\Connection;

use Firebase\JWT\JWT;

class PostController
{
  /*Peticion POST para crear nuevos registros*/
  static public function postData($table, $data)
  {
    $response = PostModel::postData($table, $data);
    $result = new PostController();
    return $result->response($response, null, null);
  }

  /*Peticion POST para registrar usuario*/
  static public function postRegister($table, $data, $suffix)
  {
    /*Valida si el dato de la contraseña biene vacio*/
    if (isset($data["password_" . $suffix]) && $data["password_" . $suffix] != null) {
      $password = $data["password_" . $suffix];
      $options = [
        'cost' => 12,
      ];
      /*Encripta la contraseña*/
      $hash = password_hash($password, PASSWORD_BCRYPT, $options);
      $data["password_" . $suffix] = $hash;
      /*Almacena los datos con la contraseña encriptada*/
      $response = PostModel::postData($table, $data);
      $result = new PostController();
      return $result->response($response, null, null);
    } else {

      /*Registro de usuarios desde redes sociales.*/
      $response = PostModel::postData($table, $data);

      if (isset($response["comment"]) && $response["comment"] == "Datos alamacenados de forma correcta") {
        /*Valida la existencia del correo electronico*/
        $response = GetModel::getDataFilter($table, "*", "email_" . $suffix, $data["email_" . $suffix], null, null, null, null);
        if (isset($response)) {
          /*Encabezado del JWT*/
          $header = array(
            "alg" => "HS256",
            "typ" => "JWT"
          );
          /*Regresa un arreglo con el cuerpo de los datos para JWT*/
          $token = Connection::jWToken($response[0]->{"id_" . $suffix}, $response[0]->{"email_" . $suffix});
          /*Crea el token JWT*/
          $jwt = JWT::encode($token, "secret_key", "HS256", null, $header);
          /*Se crea la data para actualizar los datos del token del usuario*/
          $data = array(
            "token_" . $suffix => $jwt,
            "token_exp_" . $suffix => $token["exp"]
          );
          /*Actualizar la base de datos añadiendo el token y token de expiración */
          $update = PutModel::putData($table, $data, $response[0]->{"id_" . $suffix}, "id_" . $suffix);
          if (isset($update["comment"]) && $update["comment"] == "Datos actualizados de forma correcta") {
            /*Se actualizan los datos de la respuesta que mostrara los datos*/
            $response[0]->{"token_" . $suffix} = $jwt;
            $response[0]->{"token_exp_" . $suffix} = $token;
            $result = new PostController();
            return $result->response($response, null, $suffix);
          }
        }
      }
    }
  }

  /*Peticion POST para Login*/
  static public function postLogin($table, $data, $suffix)
  {

    $password = $data["password_" . $suffix];
    /*Valida la existencia del correo electronico*/
    $response = GetModel::getDataFilter($table, "*", "email_" . $suffix, $data["email_" . $suffix], null, null, null, null);
    /*Valida si la respuesta es vacia*/
    if (empty($response)) {
      $message = "Correo y/o Contraseña Incorrecta";
      $result = new PostController();
      return $result->response(null, $message, null);
    } else {
      if ($response[0]->{"password_" . $suffix} != null) {
        /*Verifica la contraseña encriptada*/
        if (password_verify($password, $response[0]->{"password_" . $suffix})) {
          /*Encabezado del JWT*/
          $header = array(
            "alg" => "HS256",
            "typ" => "JWT"
          );
          /*Regresa un arreglo con el cuerpo de los datos para JWT*/
          $token = Connection::jWToken($response[0]->{"id_" . $suffix}, $response[0]->{"email_" . $suffix});
          /*Crea el token JWT*/
          $jwt = JWT::encode($token, "secret_key", "HS256", null, $header);
          /*Se crea la data para actualizar los datos del token del usuario*/
          $data = array(
            "token_" . $suffix => $jwt,
            "token_exp_" . $suffix => $token["exp"]
          );
          /*Actualizar la base de datos añadiendo el token y token de expiración */
          $update = PutModel::putData($table, $data, $response[0]->{"id_" . $suffix}, "id_" . $suffix);
          /*Valida si la actualizacion fue correcta*/
          if (isset($update["comment"]) && $update["comment"] == "Datos actualizados de forma correcta") {
            /*Se actualizan los datos de la respuesta que mostrara los datos*/
            $response[0]->{"token_" . $suffix} = $jwt;
            $response[0]->{"token_exp_" . $suffix} = $token;
            $result = new PostController();
            return $result->response($response, null, $suffix);
          }
        } else {
          /*Valida que la contraseña es incorrecta*/
          $message = "Correo y/o Contraseña Incorrecta";
          $result = new PostController();
          return $result->response(null, $message, null);
        }
      } else {
        /*Actualizacion de tokens de usuarios logeados de redes sociales*/
        /*Encabezado del JWT*/
        $header = array(
          "alg" => "HS256",
          "typ" => "JWT"
        );
        /*Regresa un arreglo con el cuerpo de los datos para JWT*/
        $token = Connection::jWToken($response[0]->{"id_" . $suffix}, $response[0]->{"email_" . $suffix});
        /*Crea el token JWT*/
        $jwt = JWT::encode($token, "secret_key", "HS256", null, $header);
        /*Se crea la data para actualizar los datos del token del usuario*/
        $data = array(
          "token_" . $suffix => $jwt,
          "token_exp_" . $suffix => $token["exp"]
        );
        /*Actualizar la base de datos añadiendo el token y token de expiración */
        $update = PutModel::putData($table, $data, $response[0]->{"id_" . $suffix}, "id_" . $suffix);
        /*Valida si la actualizacion fue correcta*/
        if (isset($update["comment"]) && $update["comment"] == "Datos actualizados de forma correcta") {
          /*Se actualizan los datos de la respuesta que mostrara los datos*/
          $response[0]->{"token_" . $suffix} = $jwt;
          $response[0]->{"token_exp_" . $suffix} = $token;
          $result = new PostController();
          return $result->response($response, null, $suffix);
        }
      }
    }
  }

  /*Crea las respuestas de tipo JSON*/
  public function response($response, $message, $suffix)
  {
    /*Valida que la respuesta no este vacia*/
    if (!empty($response)) {
      /*Valida que el campo de contraseña y lo retira para la respuesta*/
      if (isset($response[0]->{"password_" . $suffix})) {
        unset($response[0]->{"password_" . $suffix});
      }
      $json = array(
        "status" => 200,
        'result' => $response

      );
      echo json_encode($json, http_response_code($json["status"]));
    } else {
      /*Valida que en caso de error en la respuesta añadiendo un mensaje*/
      if ($message != null) {
        $json = array(
          "status" => 404,
          'result' => $message
        );
        echo json_encode($json, http_response_code($json["status"]));
      } else {
        /*Valida que en caso de error en la respuesta por defecto*/
        $json = array(
          "status" => 400,
          'result' => "Error al ingresar datos"
        );
        echo json_encode($json, http_response_code($json["status"]));
      }
    }
  }
}
