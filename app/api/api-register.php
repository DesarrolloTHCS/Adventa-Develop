<?php
/**
 * Author:Alfredo Segura <pixxo2010@gmail.com>
 * Description: API para registrar un usuario
 * Method: POST
 * Date: 15/06/2023
 */

use App\Models\PostModel;
use App\Models\GetModel;
use App\Traits\Validate;
require_once ($_SERVER['DOCUMENT_ROOT'].'/shop_thcs_nativo/vendor/autoload.php');

// Lee el contenido de la solicitud JSON
$requestPayload = file_get_contents('php://input');

// Decodifica el contenido JSON en un objeto PHP
$data = json_decode($requestPayload);

//Obtiene el método de la solicitud
$method=$_SERVER['REQUEST_METHOD'];

//Número de iteraciones para el algoritmo Blowfish
$options = [
  'cost' => 12,
];

//Compañía por defecto
$default_company="9999";

//Tipo de registro
$type_register=$_GET['type-register'];

if($method=="POST"){
  
  switch($type_register){

    case 'express':

    if(isset($_POST['register_email']) 
    && isset($_POST['register_password'])
    && isset($_POST['register_policy'])
    ){      
      $email=Validate::validateEmail($_POST['register_email']);      
      $password=Validate::validatePassword($_POST['register_password']);
      $policy=$_POST['register_policy'];

      if(is_array($email)){
        response($email,404);
        exit();
      }
      
      if(is_array($password)){
      response($password,404);
      exit();
    }

    if($policy!=1){
      $error = array(
        "error" => "Debes aceptar los términos y condiciones"
      );
      response($error,404);
      exit();
    }

    $register=GetModel::getDataFilter('users','email_user','email_user', $email,'id', 'ASC',0,1);
    if(!empty($register)){
    if($register[0]->email_user==$email){
      $error = array(
        "error" => "El correo no está disponible"
      );
      response($error,404);
      exit();
    }
  }

      /*Encripta la contraseña*/
      $hash = password_hash($password, PASSWORD_BCRYPT, $options);
      $data=["password" =>$hash,"email_user" => $email,"id_company"=>$default_company,"status_policy"=>$policy];
      $result=PostModel::postData('users',$data);
      print_r($result);
      response($result);

      exit();
    }else{

      if(empty($_POST['register_email'])){
        $result=[
          "error" => "El correo no puede estar vacío"
        ];
        response($result,404);
        exit();
      }
      
      if(empty($_POST['register_password'])){
        $result=[
          "error" => "La contraseña no puede estar vacía"
        ];
      response($result,404);
      exit();
    }

    if($_POST['register_policy']==0){
      $error = array(
        "error" => "Debes aceptar los términos y condiciones"
      );
      response($error,404);
      exit();
    }
    exit();
    };
    break;
    
    case 'full':
      if(isset($_POST['register_name'])
      && isset($_POST['register_paternal'])
      && isset($_POST['register_maternal'])
      && isset($_POST['register_rfc'])
      && isset ($_POST['register_sex'])
      && isset($_POST['register_phone'])
      && isset($_POST['register_cellphone'])
      && isset($_POST['register_username'])
      && isset($_POST['register_email'])
      ){
      $name=Validate::validateName($_POST['register_name']);
      $paternal=Validate::validatePaternal($_POST['register_paternal']);
      $maternal=Validate::validateMaternal($_POST['register_maternal']);
      $rfc=Validate::validateRFC($_POST['register_rfc']);
      $sex=Validate::validateSex($_POST['register_sex']);
      $phone=Validate::validatePhone($_POST['register_phone']);
      $cellPhone=Validate::validatePhone($_POST['register_cellphone']);
      $userName=Validate::validateUserName($_POST['register_username']);
      $email=Validate::validateEmail($_POST['register_email']);

      if(is_array($name)){
        response($name,404);
        exit();
      }

      if(is_array($paternal)){
        response($paternal,404);
        exit();
      }

      if(is_array($maternal)){
        response($maternal,404);
        exit();
      }

      if(is_array($rfc)){
        response($rfc,404);
        exit();
      }

      if(is_array($sex)){
        response($sex,404);
        exit();
      }

      if(is_array($phone)){
        response($phone,404);
        exit();
      }

      if(is_array($cellPhone)){
        response($cellPhone,404);
        exit();
      }

      if(is_array($userName)){
        response($userName,404);
        exit();
      }
      if(is_array($email)){
        response($email,404);
        exit();
      }

      $data=[
        'name_user'=>$name,
        'paternal_last_name_user'=>$paternal,
        'maternal_last_name_user'=>$maternal,
        'sex_user'=>$sex,
        'office_telephone_user'=>$phone,
        'celular_telephone_user'=>$cellPhone,
        'rfc_user'=>$rfc,
        'email_user'=>$email,
        'username'=>$userName,
      ];
    }else{
      $error = array(
        "error" => "Todos los campos son obligatorios"
      );
      response($error,404);

      exit();
    }
      if(isset($_POST['register_password']) && isset($_POST['register_confirm-password'])){        
      $password=Validate::validatePassword($_POST['register_password']);
      $confirmPassword=Validate::validatePassword($_POST['register_confirm-password']);
        if(is_array($password)){
          response($password,404);
          exit();
        }
        if(is_array($confirmPassword)){
          response($confirmPassword,404);
          exit();
        }

        if($password==$confirmPassword){
          $hash = password_hash($password, PASSWORD_BCRYPT, $options);
          $data['password']=$hash;
        }else{
          $error = array(
            "error" => "Las contraseñas no coinciden"
          );
          response($error,404);
          exit();
        }
      }

      $result=PostModel::postData('users',$data);
      
      response($result);
      $log=PostModel::postData('log_insert',[
        'tablename'=>'users',
        'idtable'=>$result->lastId,
        'query'=>json_encode($data),
        'id_user_reg'=>$result->lastId
      ]);
      exit();
    break;

}
}else{
  $error = array(
    "error" => "Recibe los datos por POST"
  );
  response($error,404);
  exit();
}

/*Crea las respuestas de tipo JSON*/
function response($response, $status = 200)
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
