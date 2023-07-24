<?php

namespace Views\Auth;

class Login
{
    static function login()
    {
        $html = <<<HTML
    <!-- Sign in / Register Modal -->
    <div class="modal fade" id="signin-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="icon-close"></i></span>
                    </button>

                    <div class="form-box">
                        <div class="form-tab">
                            <ul class="nav nav-pills nav-fill" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="signin-tab" data-toggle="tab" href="#signin" role="tab" aria-controls="signin" aria-selected="true">Iniciar sesión</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="register-tab" data-toggle="tab" href="#register" role="tab" aria-controls="register" aria-selected="false">Registrarte</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="tab-content-5">
                                <div class="tab-pane fade show active" id="signin" role="tabpanel" aria-labelledby="signin-tab">
                                    <form id="form-login" class="needs-validation" novalidate> 
                                    <div class="text-center alert alert-danger alert-login" style="display:none;" role="alert">Usuario y/o contraseña incorrectas 
                                    </div>
                                        <div class="form-group">
                                            <label for="singin-email">Usuario o correo electrónico</label>
                                            <input type="text" class="form-control" id="singin-email" name="singin-email" required>
                                            <div  class="valid-register">Correo valido</div>
                                        </div><!-- End .form-group -->

                                        <div class="form-group">
                                            <label for="singin-password">Contraseña</label>
                                            <input type="password" class="form-control" id="singin-password" name="singin-password" required>
                                            <div  class="valid-register">Ingrese una contraseña</div>
                                        </div><!-- End .form-group -->

                                        <div class="form-footer">
                                            <button type="submit" class="btn btn-outline-primary-2">
                                                <span>Iniciar sesión</span>
                                                <i class="icon-long-arrow-right"></i>
                                            </button>

                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="signin-remember">
                                                <label class="custom-control-label" for="signin-remember">Recordarme</label>
                                            </div><!-- End .custom-checkbox -->

                                            <a href="#" class="forgot-link">Olvide mi contraseña?</a>
                                        </div><!-- End .form-footer -->
                                    </form>
                                    <div class="form-choice">
                                        <p class="text-center">O inicia sesión con</p>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <a href="#" class="btn btn-login btn-g">
                                                    <i class="icon-google"></i>
                                                    Iniciar sesión con Google
                                                </a>
                                            </div><!-- End .col-6 -->
                                            <div class="col-sm-6">
                                                <a href="#" class="btn btn-login btn-f">
                                                    <i class="icon-facebook-f"></i>
                                                    Iniciar sesión con Facebook
                                                </a>
                                            </div><!-- End .col-6 -->
                                        </div><!-- End .row -->
                                    </div><!-- End .form-choice -->
                                </div><!-- .End .tab-pane -->
                                <div class="tab-pane fade" id="register" role="tabpanel" aria-labelledby="register-tab">
                                    <form id="form-register" class="needs-validation">
                                        <input type="hidden" name="type-register" value="register-express">
                                        <div class="form-group">
                                            <label for="register-email">Tu correo electrónico *</label>
                                            <input type="email" class="form-control" id="register_email" name="register_email">
                                            <div  class="valid-register"></div>
                                        </div><!-- End .form-group -->

                                        <div class="form-group">
                                            <label for="register-password">Nueva contraseña *</label>
                                            <input type="password" class="form-control" id="register_password" name="register_password" minlength="8" maxlength="16" >
                                            <small>   <ul>
                                                <li>8-16 caracteres</li>
                                                <li>Una letra mayuscula</li>
                                                <li>Un caracter "/*\-_$!"</li>
                                            </ul></small>
                                            <div  class="valid-register"></div>
                                        </div><!-- End .form-group -->

                                        <div class="form-footer">
                                            <button type="submit" class="btn btn-outline-primary-2">
                                                <span>Registrarte</span>
                                                <i class="icon-long-arrow-right"></i>
                                            </button>
                                            </form>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="register_policy" name="register_policy">
                                                <label class="custom-control-label" for="register_policy">Acepto las <a href="#">politicas de privacidad</a> *</label>
                                            </div><!-- End .custom-checkbox -->
                                        </div><!-- End .form-footer -->
                                    <div class="form-choice">
                                        <p class="text-center">O registrarme con</p>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <a href="#" class="btn btn-login btn-g">
                                                    <i class="icon-google"></i>
                                                    Registrarme con Google
                                                </a>
                                            </div><!-- End .col-6 -->
                                            <div class="col-sm-6">
                                                <a href="#" class="btn btn-login  btn-f">
                                                    <i class="icon-facebook-f"></i>
                                                    Registrarme con Facebook
                                                </a>
                                            </div><!-- End .col-6 -->
                                        </div><!-- End .row -->
                                    </div><!-- End .form-choice -->
                                </div><!-- .End .tab-pane -->
                            </div><!-- End .tab-content -->
                        </div><!-- End .form-tab -->
                    </div><!-- End .form-box -->
                </div><!-- End .modal-body -->
            </div><!-- End .modal-content -->
        </div><!-- End .modal-dialog -->
    </div><!-- End .modal -->
HTML;
        echo $html;
    }
}
