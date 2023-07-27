<?php

namespace Views\Auth;

use App\Traits\App;

require_once '../../vendor/autoload.php';
class Login
{

  use App;

  static function login()
  {
    $url= self::PATH;
    $path = self::PATH_LIBRARIES;
    $path_dist = self::PATH_DIST;
    $path_assets = self::PATH_ASSETS;
  if (!empty($_SESSION['id_user']) && !empty($_SESSION['id_company'])) {
       header('Location:'.$url.'views/index');
        exit();
    }
    $html = <<<HTML
        <!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sistema-Inventario-DIODI</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{$path}fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="{$path}icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="{$path_dist}css/adminlte.min.css">
</head>
    <body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="../../index2.html"><b>DIODI</b><br>PROYECTO JENNY</a>
  </div>
  <!-- /.login-logo -->
  <div class="card" style="display:block" id="card-login">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Ingresa tusa datos para iniciar sesión</p>

      <form id="form-login">
        <div class="input-group mb-3">
          <input type="email" class="form-control" placeholder="Correo electrónico" name="singin_email" id="singin-email">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
          <div class="col-12">
            <label class="valid-register"></label>
            </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Contraseña" name="singin_password" id="singin-password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>           
          </div>
          <div class="col-12">
            <label class="valid-register"></label>
            </div>
        </div>
        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" id="remember">
              <label for="remember">
                Recordarme
              </label>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">Entrar</button>
          </div>
          <!-- /.col -->
        </div>
      </form>

      <p class="mb-1">
        <a href="forgot-password.html">Olvide mi contraseña</a>
      </p>
      <p class="mb-0">
        <a href="#" onClick="showCardRegister()" class="text-center">Registrarme</a>
      </p>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->
HTML;
    echo $html;
  }

  static function register()
  {
    $html = <<<HTML
     <div class="card" style="display:none;" id="card-register">
    <div class="card-body register-card-body">
      <p class="login-box-msg">Registro express</p>

      <form id="form-register">
        <div class="input-group mb-3">
          <input type="email" class="form-control" placeholder="Correo electrónico" name="register_email" id="register_email">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
          <div class="col-12">
            <label class="valid-register"></label>
            </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Contraseña" name="register_password" id="register_password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
          <div class="col-12">
            <label class="valid-register"></label>
            </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Confirmar contraseña" name="register_confirm_password" id=register_confirm_password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
          <div class="col-12">
            <label class="valid-register"></label>
            </div>
        </div>
        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox"  id="register_policy" name="register_policy">
              <label for="register_policy">
               Aceptar <a href="#">terminos y condiciones</a>
              </label>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">Registrar</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
      <a href="#" onClick="showCardLogin()" class="text-center">Ya tengo una cuenta</a>
    </div>
    <!-- /.form-box -->
  </div><!-- /.card -->
HTML;
    echo $html;
  }

  static function scripts()
  {
    $path = self::PATH_LIBRARIES;
    $path_dist = self::PATH_DIST;
    $path_assets = self::PATH_ASSETS;
    $html = <<<JS
<!-- jQuery -->
<script src="{$path}jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="{$path}bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- AdminLTE App -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{$path_dist}js/adminlte.min.js"></script>
<script src="{$path_assets}js/app.js"></script>
<script src="{$path_assets}js/auth/validation.js"></script>
<script src="{$path_assets}js/auth/login.js"></script>
<script src="{$path_assets}js/auth/register.js"></script>
</body>
</html>
JS;
    echo $html;
  }
}
Login::login();
Login::register();
Login::scripts();
