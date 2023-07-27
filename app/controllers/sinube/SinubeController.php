<?php

namespace App\Controllers\Sinube;

class SinubeController
{


    static function consultar($store = "CAPITAL SAPI", $price_list = "Adventa", $cursor = null)
    {
/* 
        $store = 'ECOMMERCE';   
        $price_list = 'Ecommerce';  */
        $cursor = null;
        $json = array();

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
        print_r($temporal);
        return;
        foreach ($temporal as $key => $li) {
            if ($key == 0) {
                continue;
            }
            $linea_re   = explode('|', $li);

            if (count($linea_re) == 9) {
                $json[] = [
                    'rfc'           => $linea_re[1],
                    'existencias'   => $linea_re[2],
                    'producto'      => $linea_re[3],
                    'sucursal'      => $linea_re[4],
                    'precio'        => $linea_re[7],
                    'precioMinimo'    => $linea_re[8],
                    'descripcion'   => $linea_re[0],
                ];
            }
        }
        return $json;
    }

    static function processingSinube($resultado)
    {

        $cursor = null;
        $lineas = [];

        do {
            //echo "<br> Iniciando consulta";
            $temporal   = explode('¬', $resultado);
            $linea_re   = explode('|', $temporal[0]);
            $cursor     = ($linea_re[1] == '&NullSiNube;') ? null : $linea_re[1];
            foreach ($temporal as $li) {
                $lineas[] = $li;
            }
        } while ($cursor != null);

        //$lineas = explode('¬', $resultado);

        $json = [];
        $json[] = [
            'rfc'           => 'RFC',
            'existencias'   => 'EXISTENCIAS',
            'producto'      => 'PRODUCTO',
            'sucursal'      => 'SUCURSAL',
            'precio'        => 'PRECIO',
            'precioMinimo'    => 'PRECIO MINIMO',
            'descripcion'    => 'DESCRIPCION',
        ];

        foreach ($lineas as $linea) {
            $contenido = explode('|', $linea);
            //echo "<pre>";
            //print_r($contenido);
            //echo "</pre>";
            if (count($contenido) == 9) {
                $json[] = [
                    'rfc'           => $contenido[1],
                    'existencias'   => $contenido[2],
                    'producto'      => $contenido[3],
                    'sucursal'      => $contenido[4],
                    'precio'        => $contenido[7],
                    'precioMinimo'    => $contenido[8],
                    'descripcion'   => $contenido[0],
                ];
            }
        }

        echo json_encode($json);
    }
}
