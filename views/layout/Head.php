<?php

namespace Views\Layout;

use App\Traits\App;



class Head
{

    use App;

    static function head($title = "Tuhogarconsentido")
    {
        session_start();

        $path = self::PATH_LIBRARIES;
        $path_dist = self::PATH_DIST;


        $html = <<<HTML
<!DOCTYPE html>
<html lang="es-MX">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="keywords" content="electrónica,hogar,jardineria,linea blancka,radios,vajillas">
    <meta name="description" content="Tu hogar consentido, tienda de productos varios">
    <meta name="author" content="tu hogar con sentido">
    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="{$path}public/assets/images/images/logo-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="{$path}public/assets/images/logo-icon.png">
    <link rel="icon" type="image/png" sizes="16x16" href="{$path}public/assets/images/logo-icon.png">
    <link rel="manifest" href="{$path}public/assets/images/icons/site.html">
    <link rel="mask-icon" href="{$path}public/assets/images/icons/safari-pinned-tab.svg" color="#666666">
    <link rel="shortcut icon" href="{$path}public/assets/images/logo-icon.png">
    <meta name="apple-mobile-web-app-title" content="">
    <meta name="application-name" content="Tu hogar consentido shop">
    <meta name="msapplication-TileColor" content="#cc9966">
    <meta name="msapplication-config" content="{$path}public/assets/images/browserconfig.xml">
    <meta name="theme-color" content="#ffffff">
    <link rel="stylesheet" href="{$path}public/assets/vendor/line-awesome/line-awesome/line-awesome/css/line-awesome.min.css">
   <!--Meta-->

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="{$path}fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="{$path_dist}css/adminlte.min.css">
  <!-- summernote -->
  <link rel="stylesheet" href="{$path}summernote/summernote-bs4.min.css">
  <link rel="stylesheet" href="{$path}sweetalert2/sweetalert2.min.css">
  <!-- CodeMirror -->
  <link rel="stylesheet" href="{$path}codemirror/codemirror.css">
  <link rel="stylesheet" href="{$path}codemirror/theme/monokai.css">
 <!-- DataTables -->
  <link rel="stylesheet" href="{$path}datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="{$path}datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="{$path}datatables-buttons/css/buttons.bootstrap4.min.css">
    <title>$title</title>
</head>
HTML;
        echo $html;
    }
}
