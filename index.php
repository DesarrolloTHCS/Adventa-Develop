<?php
require_once __DIR__ . '\vendor\autoload.php';
//
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('erro_log', "C:/xampp/htdocs/apiRestFull");

/*CORS */
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origins, X-Request-With, Content-Type, Accept");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("content-type:application/json; charset=utf-8");

header('location: views/auth/Login');
exit();

/* $index=new RouterController();
$index->index(); */
