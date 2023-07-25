<?php
/**
 * Author: Alfredo Segura <pixxo2010@gmail.com>
 * Description: API para actualizar los datos del usuario
 * Method: PUT
 * Date: 15/06/2023
*/

use App\Models\GetModel;
use App\Models\PutModel;
use App\Traits\Validate;
require_once ($_SERVER['DOCUMENT_ROOT'].'/Adventa-Develop/vendor/autoload.php');


session_start();
if (isset($_SESSION['id_user'])) {
    
    // Lee el contenido de la solicitud JSON
    $requestPayload = file_get_contents('php://input');

    // Decodifica el contenido JSON en un objeto PHP
    $data = json_decode($requestPayload);
    
    //Obtiene el método de la solicitud
    $method = $_SERVER['REQUEST_METHOD'];
    //Número de iteraciones para el algoritmo Blowfish
    $options = [
        'cost' => 12,
    ];
    //Compañía por defecto
    $default_company = "9999";

    if ($method == "PUT") {
        if (
            isset($data->register_name)
            && isset($data->register_paternal)
            && isset($data->register_maternal)
            && isset($data->register_rfc)
            && isset($data->register_sex)
            && isset($data->register_phone)
            && isset($data->register_cellphone)
            && isset($data->register_username)
            && isset($data->register_email)
        ) {
            $name = Validate::validateName($data->register_name);
            $paternal = Validate::validatePaternal($data->register_paternal);
            $maternal = Validate::validateMaternal($data->register_maternal);
            $rfc = Validate::validateRFC($data->register_rfc);
            $sex = Validate::validateSex($data->register_sex);
            $phone = Validate::validatePhone($data->register_phone);
            $cellPhone = Validate::validatePhone($data->register_cellphone);
            $userName = Validate::validateUserName($data->register_username);
            $email = Validate::validateEmail($data->register_email);
            $id_user = $_SESSION['id_user'];
            

            if (is_array($name)) {
                response($name, 404);
                exit();
            }

            if (is_array($paternal)) {
                response($paternal, 404);
                exit();
            }

            if (is_array($maternal)) {
                response($maternal, 404);
                exit();
            }

            if (is_array($rfc)) {
                response($rfc, 404);
                exit();
            }

            if (is_array($sex)) {
                response($sex, 404);
                exit();
            }

            if (is_array($phone)) {
                response($phone, 404);
                exit();
            }

            if (is_array($cellPhone)) {
                response($cellPhone, 404);
                exit();
            }

            if (is_array($userName)) {
                response($userName, 404);
                exit();
            }
            if (is_array($email)) {
                response($email, 404);
                exit();
            }

            $data = [
                'name_user' => $name,
                'paternal_last_name_user' => $paternal,
                'maternal_last_name_user' => $maternal,
                'sex_user' => $sex,
                'office_telephone_user' => $phone,
                'celular_telephone_user' => $cellPhone,
                'rfc_user' => $rfc,
                'email_user' => $email,
                'username' => $userName,
            ];
        } else {
            $error = array(
                "error" => "Todos los campos son obligatorios"
            );
            response($error, 404);
            exit();
        }
        if (
            isset($data->register_password)
            && isset($data->register_confirm_password)
        ) {
            $password = Validate::validatePassword($data->register_password);
            $confirmPassword = Validate::validatePassword($data->register_confirm_password);
            if (is_array($password)) {
                response($password, 404);
                exit();
            }
            if (is_array($confirmPassword)) {
                response($confirmPassword, 404);
                exit();
            }

            if ($password == $confirmPassword) {
                $hash = password_hash($password, PASSWORD_BCRYPT, $options);
                $data['password'] = $hash;
            } else {
                $error = array(
                    "error" => "Las contraseñas no coinciden"
                );
                response($error, 404);
                exit();
            }
        }

        $validateEmail=GetModel::getDataFilter('users','id_user,email_user','email_user',$email,null,null,null,null);
        if($validateEmail && $validateEmail[0]->id_user!=$id_user){
            $error = array(
                "error" => "El correo no esta disponible"
            );
            response($error, 404);
            exit();
        }

        $result = PutModel::putData('users', $data, $id_user,'id_user');
        response($result);
        exit();
    } else {
        $error = array(
            "error" => "Recibe los datos por PUT"
        );
        response($error, 404);
        exit();
    }
} else {
    $error = array(
        "error" => "Se requiere autorización"
    );
    response($error, 404);
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
