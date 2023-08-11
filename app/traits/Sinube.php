<?php
namespace App\Traits;


use DateTime;
use DateTimeZone;

Trait Sinube{

    const PORCENTAJE_IVA = 16;

    const MONEDA_SINUBE = 'MXN';

    const DIFERENCIA_HORARIA = -6;

    const SUCURSAL = "Matriz";

    static function folioAutofacturacion(int $id_order){
    // Crear un objeto DateTime con la zona horaria de México
       $timezone = new DateTimeZone('America/Mexico_City');
       $datetime = new DateTime('now', $timezone);

        // Formatear la fecha y hora
        $time = $datetime->format('Ymdhis');
        $folio = $id_order.$time;
        return $folio;

    }

}