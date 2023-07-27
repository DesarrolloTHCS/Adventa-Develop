<?php

namespace Views\Layout;

use Views\Layout\Head;
use Views\Layout\NavBar;
use Views\Layout\AsideBar;
use Views\Layout\Footer;
use Views\Layout\Scripts;
use Views\Components\Auth\Login;

class Layout
{

    static public function layoutHeader()
    {
        Head::head('Sistema de inventario');
        echo '<body class="hold-transition sidebar-mini layout-fixed">';
        echo '<div class="wrapper">';
        NavBar::navBar();
        AsideBar::asideBar();
        echo '<div class="content-wrapper">';       
    
    }
    
    static public function layoutFooter(){
        echo '</div>';    
        Footer::footer();
        Scripts::scripts();
        echo '</div></body></html>';
    }
}
?>