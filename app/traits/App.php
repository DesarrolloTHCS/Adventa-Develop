<?php
namespace App\Traits;

use DateTime;
use DateTimeZone;

Trait App{

    /**
     * Author: Alfredo Segura <pixxo2010@gmail.com>
     * URL DEL PROYECTO  PRINCIPAL
     */
    public const PATH = 'http://127.0.0.1/Adventa-Develop/';

    
    /**
     * Author: Alfredo Segura <pixxo2010@gmail.com>
     * RUTA DONDE OBTENTRA LOS PLUGINS DEL PROYECTO
     */
    
    public const PATH_LIBRARIES='/Adventa-Develop/public/library/';

        /**
     * Author: Alfredo Segura <pixxo2010@gmail.com>
     * RUTA DONDE OBTENTRA LOS ASSETS DEL PLUGIN DEL PROYECTO
     */
    
     public const PATH_DIST='/Adventa-Develop/public/dist/';


    
    /**
     * Author: Alfredo Segura <pixxo2010@gmail.com>
     * RUTA DONDE OBTENTRA LAS IMAGENES DE LOS PRODUCTOS
     */

    public const PATH_IMAGES_PUBLICS='/Adventa-Develop/public/assets/';

    /**
     * Author: Alfredo Segura <pixxo2010@gmail.com>
     * RUTA DONDE OBTENTRA LAS IMAGENES DE LOS PRODUCTOS
     */

    public const PATH_STORAGE='/Adventa-Develop/storage/';

    /**
     * Author: Alfredo Segura <pixxo2010@gmail.com>
     * RUTA DONDE DE ASSETS
     */
    
    public const PATH_ASSETS='/Adventa-Develop/views/assets/';

    //Enlace página facebook
    public const FACEBOOK='https://www.facebook.com/tuhogarconsentidomx/';
    //Enlace página instagram
    public const INSTAGRAM='https://www.instagram.com/tuhogarconsentidomx/';
    //Enlace página linkedin
    public const LINKEDIN='https://mx.linkedin.com/company/tu-hogar-con-sentido';
    
    //La tasa del IVA del 16% se expresa como 0.16
    public const TASA_IVA=0.16;

    //Factor para calcular el precio con IVA
    public const FACTOR_TOTAL_IVA=1.16;

    public function url(){
       $url= $_SERVER['DOCUMENT_ROOT'] . '/Adventa-Develop/';
       return $url;
    }

    static function getCurrentTime(){
    // Crear un objeto DateTime con la zona horaria de México
    $timezone = new DateTimeZone('America/Mexico_City');
    $datetime = new DateTime('now', $timezone);

    // Formatear la fecha y hora
    $time = $datetime->format('Y-m-d h:i:s');

    return $time;
    }

    
}