<?php
namespace App\Controllers\Sinube;

class SinubeController{


static function consultar($store="BODEGUITA", $price_list="Ecommerce", $cursor=null){


    $empresa    = 'DOD021211S63';
    $usuario    = 'contabilidad.diodi10@gmail.com';
    $password   = 'KEPF2R3E';
    $cursor     = ($cursor == null) ? '' : " CURSOR {$cursor}";
    $consulta   = "SELECT P.descripcion, L.empresa, L.existencia, L.producto, L.sucursal, P.activo, L.almacen, PP.precio, PP.precioMinimo FROM DbProducto AS P INNER JOIN DbInvProductoLote AS L ON L.producto = P.producto AND L.empresa = P.empresa INNER JOIN DbProductoPrecio AS PP ON PP.producto = P.producto AND PP.empresa = P.empresa WHERE PP.listaPrecios = '".$price_list."' AND P.empresa = 'DOD021211S63' AND P.activo = true AND L.almacen= '".$store."' AND PP.listaPrecios = '".$price_list."'{$cursor}";
        //echo "<br>Realizando la consulta {$consulta}";
    $urlf       = "http://getpost.si-nube.appspot.com/getpost";   
        //$urlf       = "http://getpost.facturanube.appspot.com/getpost";   
        //Pagina DIODI
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $urlf);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "tipo=3&emp={$empresa}&suc=Matriz&usu={$usuario}&pas={$password}&cns={$consulta}");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $resultado = curl_exec ($ch);
    curl_close ($ch);
   /*  return $resultado; */
   $temporal   = explode('¬', $resultado);
   $linea_re   = explode('|', $temporal[0]);
   $cursor     = ($linea_re[1] == '&NullSiNube;') ? null : $linea_re[1];
   print_r($cursor);
    /* self::processingSinube($resultado); */


}

static function processingSinube($resultado){

$cursor = null;
$lineas = [];

do{
    //echo "<br> Iniciando consulta";
    $temporal   = explode('¬', $resultado);
    $linea_re   = explode('|', $temporal[0]);
    $cursor     = ($linea_re[1] == '&NullSiNube;') ? null : $linea_re[1];
    $incremetn = 0;
    foreach($temporal as $li){
        if($incremetn==1){
            break;
        }
        $lineas[] = $li;
        $incremetn++;
        
    }
}while($cursor != null);

    //$lineas = explode('¬', $resultado);

$json = [];
$json[] = [
    'rfc'           => 'RFC',
    'existencias'   => 'EXISTENCIAS',
    'producto'      => 'PRODUCTO',
    'sucursal'      => 'SUCURSAL',
    'precio'		=> 'PRECIO',
    'precioMinimo'	=> 'PRECIO MINIMO',
    'descripcion'	=> 'DESCRIPCION',
];

foreach($lineas as $linea){
    $contenido = explode('|', $linea);
		//echo "<pre>";
		//print_r($contenido);
		//echo "</pre>";
    if(count($contenido) == 9){
        $json[] = [
            'rfc'           => $contenido[1],
            'existencias'   => $contenido[2],
            'producto'      => $contenido[3],
            'sucursal'      => $contenido[4],
            'precio'		=> $contenido[7],
            'precioMinimo'	=> $contenido[8],
            'descripcion'   => $contenido[0],
        ];

    }
}

echo json_encode($json);
}


}