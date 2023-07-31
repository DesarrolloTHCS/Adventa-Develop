<?php

namespace App\Controllers\Sinube;

use App\Controllers\Catalogs\CatalogProductsCOntroller;
class SinubeController
{


    static function consultar($store = "CAPITAL SAPI", $price_list = "Adventa", $cursor = null)
    {
/* 
        $store = 'ECOMMERCE';   
        $price_list = 'Ecommerce';  */
        $cursor = null;
        $products = array();

        $empresa    = 'DOD021211S63';
        $usuario    = 'contabilidad.diodi10@gmail.com';
        $password   = 'KEPF2R3E';
        $cursor     = ($cursor == null) ? '' : " CURSOR {$cursor}";
        $consulta   = "SELECT P.descripcion, P.marca, P.codigoAuxiliar, L.empresa, L.existencia, L.producto, L.sucursal, P.activo, L.almacen, PP.precio, PP.precioMinimo FROM DbProducto AS P INNER JOIN DbInvProductoLote AS L ON L.producto = P.producto AND L.empresa = P.empresa INNER JOIN DbProductoPrecio AS PP ON PP.producto = P.producto AND PP.empresa = P.empresa WHERE PP.listaPrecios = '" . $price_list . "' AND P.empresa = 'DOD021211S63' AND P.activo = true AND L.almacen= '" . $store . "' AND PP.listaPrecios = '" . $price_list . "'{$cursor}";

       /*  $empresa    = 'THS19060348A';
        $usuario    = 'contabilidad.diodi9@gmail.com';
        $password   = 'THCS2021@1';
        $cursor     = ($cursor == null) ? '' : " CURSOR {$cursor}";
        $consulta   = "SELECT P.descripcion, P.marca L.empresa, L.existencia, L.producto, L.sucursal, P.activo, L.almacen, PP.precio, PP.precioMinimo FROM DbProducto AS P INNER JOIN DbInvProductoLote AS L ON L.producto = P.producto AND L.empresa = P.empresa INNER JOIN DbProductoPrecio AS PP ON PP.producto = P.producto AND PP.empresa = P.empresa WHERE PP.listaPrecios = '".$price_list."' AND P.empresa = 'THS19060348A' AND P.activo = true AND L.almacen= '".$store."' AND PP.listaPrecios = '".$price_list."'{$cursor}"; */

        $urlf       = "http://getpost.si-nube.appspot.com/getpost";

        //$urlf       = "http://getpost.facturanube.appspot.com/getpost";   
        //Pagina DIODI

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $urlf);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "tipo=3&emp={$empresa}&suc=Matriz&usu={$usuario}&pas={$password}&cns={$consulta}");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $resultado = curl_exec($ch);
        curl_close($ch);
        $temporal   = explode('¬', $resultado);
        foreach ($temporal as $key => $li) {
            if ($key == 0) {
                continue;
            }
            $linea_re   = explode('|', $li);
            /* if (count($linea_re) == 10) { */
                $products[] = [
                    'descripcion'   => $linea_re[0],
                    'marca'           => $linea_re[1],
                    'categoria'   => $linea_re[2],
                    'empresa'      => $linea_re[3],
                    'existencia'      => $linea_re[4],
                    'producto'      => $linea_re[5],
                    'precio'        => $linea_re[9],
                    'precioMinimo'    => $linea_re[10],
                    
                ];
            /* } */
        }
        $sync=CatalogProductsCOntroller::asyncProductsBySinube($products);
        return $products;
    }
}
