<?php
namespace App\Traits;

Trait Validate{

  static function validateName($name){
    if($name==""){
      $response=[
        "error" => "El nombre no puede estar vacio"
      ];
      return $response;
    }
    $result=strtoupper(trim(filter_var($name,FILTER_SANITIZE_SPECIAL_CHARS)));
    if (preg_match("/^[a-zA-Z]+$/", $result) == false) {
      $result=[
        "error" => "Caracteres no validos"
      ];
      return $result;
    }else{
      return $result;
    }
  }

  static function validatePaternal($name){
    if($name==""){
      $response=[
        "error" => "El apellido paterno no puede estar vacio"
      ];
      return $response;
    }
    $result=strtoupper(trim(filter_var($name,FILTER_SANITIZE_SPECIAL_CHARS)));
    if (preg_match("/^[a-zA-Z]+$/", $result) == false) {
      $result=[
        "error" => "Caracteres no validos"
      ];
      return $result;
    }else{
      return $result;
    }
  }

  static function validateMaternal($name){
    if($name==""){
      $response=[
        "error" => "El apellido materno no puede estar vacio"
      ];
      return $response;
    }
    $result=strtoupper(trim(filter_var($name,FILTER_SANITIZE_SPECIAL_CHARS)));
    if (preg_match("/^[a-zA-Z]+$/", $result) == false) {
      $result=[
        "error" => "Caracteres no validos"
      ];
      return $result;
    }else{
      return $result;
    }
  }

  static function validateEmail($email){
    $email=strtoupper(trim(filter_var($email,FILTER_SANITIZE_EMAIL)));
    $validate=filter_var($email, FILTER_VALIDATE_EMAIL);
    if($email==false || $validate==false){
      $response=[
        "error" => "Correo electrónico no valido",
      ];
      return $response;
    }else{
      return $email;
    }
  }
  static function validatePhone($phone){
    if($phone==""){
      $response=[
        "error" => "El teléfono no puede estar vacío"
      ];
      return $response;
    }
    $result=trim(filter_var($phone,FILTER_SANITIZE_NUMBER_INT));
    if (preg_match("/^\d+$/", $result) == false) {
      $result=[
        "error" => "El teléfono no puede contener letras"
      ];
      return $result;
    }else{
      return $result;
    }
  }
  static function validateMassege($massege){
    if($massege==""){
      $response=[
        "error" => "El mensaje no puede estar vacío"
      ];
      return $response;
    }
    $result=trim(filter_var($massege,FILTER_SANITIZE_SPECIAL_CHARS));
    if ($result == "") {
      $result=[
        "error" => "El mensaje no puede estar vacío"
      ];
      return $result;
    }else{
      return $result;
    }
  }

  static function validatePassword($password) {
    $longitudMinima = 8;
    $longitudMaxima = 16;
    $caracteresEspeciales = '/[\/\-\_$!]/';

    if ($password=="") {
      $result=[
        "error" => "La contraseña no puede estar vacía"
      ];
      return $result;
    }
  
    if (strlen($password) < $longitudMinima || strlen($password) > $longitudMaxima) {
      $result=[
        "error" => "La contraseña debe tener entre 8 y 16 caracteres"
      ];
      return $result;
    }
  
    if (!preg_match('/[A-Z]/', $password)) {
      $result=[
        "error" => "La contraseña debe tener al menos una letra mayúscula"
      ];
      return $result;
    }
  
    if (!preg_match($caracteresEspeciales, $password)) {
      $result=[
        "error" => "La contraseña debe tener al menos un caracter especial"
      ];
      return $result;
    }
  
    return $password;
  }

  static function validateRFC($rfc) {
    if($rfc==""){
      $response=[
        "error" => "El RFC no puede estar vacío"
      ];
      return $response;
    }
    // Sanitizar el input
    $rfc = strtoupper(trim($rfc)); // Convertir a mayúsculas y eliminar espacios al inicio y final
    
    // Patrón de validación para RFC
    $pattern = '/^[A-Z]{4}[0-9]{6}[A-Z0-9]{3}$/';
    
    // Validar el RFC utilizando el patrón
    if (preg_match($pattern, $rfc)) {
      return $rfc; // El RFC cumple con el formato esperado
    } else {
      $result=[
        "error" => "El RFC no cumple con el formato esperado"
      ];
      return $result; // El RFC no cumple con el formato esperado
    }
}

static function validateSex($sex) {
  if($sex==""){
    $response=[
      "error" => "El sexo no puede estar vacío"
    ];
    return $response;
  }

  $sex = trim($sex); // Eliminar espacios al inicio y final
  
  // Validar si el valor es 1 o 2
  if ($sex === '1' || $sex === '2') {
    return $sex; // El valor es válido
  } else {
    $result=[
      "error" => "El sexo no puede estar vacío"
    ];
    return $result; // El valor no es válido
  }
}

static function validateUserName($username) {
  if($username==""){
    $response=[
      "error" => "El nombre de usuario no puede estar vacío"
    ];
    return $response;
  }
  // Sanitizar el input
  $username = trim($username); // Eliminar espacios al inicio y final
  $username = htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); // Convertir caracteres especiales en entidades HTML
  
  // Patrón de validación para el nombre de usuario
  $pattern = '/^[a-zA-Z0-9_\-]+$/';
  
  // Validar el nombre de usuario utilizando el patrón
  if (preg_match($pattern, $username)) {
    return $username; // El nombre de usuario es válido
  } else {
    $result=[
      "error" => "El nombre de usuario contiene caracteres no permitidos"
    ];
    return $result; // El nombre de usuario contiene caracteres no permitidos
  }
}

static function validateSearch($search) {
   // Eliminar espacios en blanco al inicio y al final
   $search = trim($search);

   // Eliminar etiquetas HTML y JavaScript de forma segura
   $search = htmlspecialchars($search, ENT_QUOTES | ENT_HTML5, 'UTF-8');
   
   // Escapar caracteres especiales para evitar la inyección de SQL
   $search = addslashes($search);

  // Devolver el valor sanitizado y validado
  
    return $search;
}

}