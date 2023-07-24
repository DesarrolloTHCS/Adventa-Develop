<?php
namespace Views\Layout;

use App\Traits\App;
use App\Traits\UserData;

class Footer{

    use App;
    use UserData;
    
    static public function footer(){

        $html=<<<HTML
        
        <footer class="main-footer">
    <div class="float-right d-none d-sm-block">
      <b>Version</b> 1.0.0
    </div>
    <strong>Copyright &copy; 2023 <a href="#">GrupoAL</a>.</strong>All rights reserved.
  </footer>

HTML;
        echo $html;
    }
}
?>