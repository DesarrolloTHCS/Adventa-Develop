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
        Head::head('TuHogarConSentido');
        echo '<body';
        NavBar::navBar();
        AsideBar::asideBar(); 
        
    }

    static public function layoutFooter(){
        
        Footer::footer();
        Scripts::scripts();
        echo '</body></html>';
    }
}
?>