<?php

namespace App\Controllers\Sinube;

use App\Controllers\Catalogs\CatalogProductsCOntroller;
use App\Models\GetModel;

class SinubeController
{

    const URL_GETPOST_SINUBE = "http://getpost.si-nube.appspot.com/getpost";

    const URL_BLOB_SINUBE = "http://ep-dot-si-nube.appspot.com/blob?par=";

    static function consultar($store = "CAPITAL SAPI", $price_list = "Adventa", $cursor = null)
    {

        /* $store = 'ECOMMERCE';   
        $price_list = 'Ecommerce'; */
        //Conta1500#
        $cursor = null;
        $products = array();
        $empresa    = 'DOD021211S63';
        $usuario    = 'contabilidad.diodi10@gmail.com';
        $password   = 'KEPF2R3E';
        $cursor     = ($cursor == null) ? '' : " CURSOR {$cursor}";
        $consulta   = "SELECT P.descripcion, P.marca, P.codigoAuxiliar, L.empresa, L.existencia, L.producto, L.sucursal, P.activo, L.almacen, PP.precio, PP.precioMinimo FROM DbProducto AS P INNER JOIN DbInvProductoLote AS L ON L.producto = P.producto AND L.empresa = P.empresa INNER JOIN DbProductoPrecio AS PP ON PP.producto = P.producto AND PP.empresa = P.empresa WHERE PP.listaPrecios = '" . $price_list . "' AND P.empresa = 'DOD021211S63' AND P.activo = true AND L.almacen= '" . $store . "' AND PP.listaPrecios = '" . $price_list . "'{$cursor}";

        /* $consulta   = "SELECT P.descripcion, P.marca, P.codigoAuxiliar, L.empresa, L.existencia, L.producto, L.sucursal, P.activo, L.almacen, PP.precio, PP.precioMinimo FROM DbProducto AS P INNER JOIN DbInvProductoLote AS L ON L.producto = P.producto AND L.empresa = P.empresa INNER JOIN DbProductoPrecio AS PP ON PP.producto = P.producto AND PP.empresa = P.empresa WHERE PP.listaPrecios = '" . $price_list . "' AND P.empresa = 'DOD021211S63' AND P.activo = true AND L.almacen= '" . $store . "' AND PP.listaPrecios = '" . $price_list . "'{$cursor}";  */

        /* $empresa    = 'THS19060348A';
        $usuario    = 'contabilidad.diodi9@gmail.com';
        $password   = 'THCS2021@1';
        $cursor     = ($cursor == null) ? '' : " CURSOR {$cursor}";
        $consulta   = "SELECT P.descripcion, P.marca, L.empresa, L.existencia, L.producto, L.sucursal, P.activo, L.almacen, PP.precio, PP.precioMinimo FROM DbProducto AS P INNER JOIN DbInvProductoLote AS L ON L.producto = P.producto AND L.empresa = P.empresa INNER JOIN DbProductoPrecio AS PP ON PP.producto = P.producto AND PP.empresa = P.empresa WHERE PP.listaPrecios = '".$price_list."' AND P.empresa = 'THS19060348A' AND P.activo = true AND L.almacen= '".$store."' AND PP.listaPrecios = '".$price_list."'{$cursor}"; */

        $urlf       = self::URL_GETPOST_SINUBE;

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
                'marca'           => $linea_re[1] ?? "sin marca",
                'categoria'   => $linea_re[2],
                'empresa'      => $linea_re[3],
                'existencia'      => $linea_re[4],
                'producto'      => $linea_re[5],
                'precio'        => $linea_re[9],
                'precioMinimo'    => $linea_re[10] ?? 0


            ];
            /* if(count($linea_re) == 9){
                    $json[] = [
                        'descripcion'   => $linea_re[0],
                        'marca'           => $linea_re[1]??"sin marca",
                        'categoria'   => $linea_re[2],
                        'empresa'      => $linea_re[3],
                        'existencia'      => $linea_re[4],
                        'producto'      => $linea_re[5],
                        'precio'        => $linea_re[9],
                        'precioMinimo'    => $linea_re[10]??0,
                        
                    ]; */
        }
        $sync = CatalogProductsCOntroller::asyncProductsBySinube($products);

        return $products;
    }

    static function createSalesNote($id_order, $store)
    {
        $order_id = $id_order;
        $store = "CAPITAL SAPI";
        $urlf = self::URL_BLOB_SINUBE;
        $conceptos = '';
        $subtotal = 0.0;
        $iva = 0.0;

        $mensaje = "ENVIO GRATIS";
        if ($datos[0]['shipping_cost'] > 0) {
            $mensaje = "ENVIO CON COSTO";
        }
        $xml = '<?xml version="1.0" encoding="utf-8"?>
        <Comprobante 
        sistema="TuHogarConSentido" 
        rfcEmisor="THS19060348A" 
        sucursal="Matriz" 
        almacen="' . $store . '"
        permiteAgregarProductosNoInv="0" folioAutofacturacion="{{folioAutofacturacion}}" formaDePago="03"
        observacion="ID DEL PEDIDO # ' . ($datos[0]['is_b2b'] ? 'B' : '') . $id_order . '" 
        referencia="E-COMMERCE ' . $mensaje . '" 
        subtotal="{{subtotal}}" 
        descuento="0" 
        porcentajeIVA="16" 
        desglosaIEPS="0" 
        montoIVA="{{iva}}" 
        montoIEPS="0" 
        total="{{total}}" 
        monedaSinube="MXN" 
        difZonaHoraria="-6">

          <Receptor 
          cliente= "" 
          rfc="XAXX010101000" 
          razonSocial="VENTA ECOMMERCE" 
          nombre="VENTA" 
          apellidoPaterno="ECOMMERCE" 
          apellidoMaterno="" esPersonaFisica="1"
          />
          <Conceptos>
          {{conceptos}}
          </Conceptos>
        </Comprobante>';



        $concepto = '<Concepto productoSinube="{{sinnube_id}}" descripcion="{{products_name}}" cantidad="{{cantidad}}" unidadSinube="{{unidad}}"
        valorUnitario="{{valorUnitario}}" descuento="0" tipoIVA="Causa IVA" montoBaseIVA="{{montoBaseIVA}}" montoIVA="{{montoIVA}}"
        importe="{{importe}}" subtotalDet="{{subtotalDet}}"/>';
        
        $productos = 0;
        $productos_para_descuento = 0;
        if($datos[0]['shipping_cost'] > 0){
            $productos++;
        }
        foreach($datos as $linea){ 
            $productos++;
            $productos_para_descuento++;
        }
        $descuento_prod = $descuento / $productos_para_descuento;

        foreach($datos as $linea){ 
            $temporal = $concepto;
            foreach($linea as $key => $valor){
                $temporal = str_replace('{{'. $key .'}}', htmlspecialchars($valor), $temporal);
            }
            //$importe    = $linea['final_price'] / 1.16;
            $importe    = $descuento > 0 ? ($linea['final_price']-$descuento_prod)/1.16 : $linea['final_price'] / 1.16;
            $temporal   = str_replace('{{importe}}', Utilerias::redondear($importe), $temporal);
            $temporal   = str_replace('{{unidad}}', 'PZA', $temporal);
            $temporal   = str_replace('{{montoBaseIVA}}', Utilerias::redondear($importe), $temporal);
            $temporal   = str_replace('{{subtotalDet}}', Utilerias::redondear($importe), $temporal);
            $temporal   = str_replace('{{montoIVA}}', Utilerias::redondear($importe * .16), $temporal);
            $temporal   = str_replace('{{valorUnitario}}', Utilerias::redondear($importe / $linea['cantidad'], 3), $temporal);
            $conceptos .= $temporal;
            $subtotal += Utilerias::redondear($importe);
            $iva += Utilerias::redondear($importe * .16);
        }

        if($datos[0]['shipping_cost'] > 0){
            $temporal = $concepto;
            //$importe = $datos[0]['shipping_cost'] / 1.16;
            $importe = $descuento > 0 ? ($datos[0]['shipping_cost'])/1.16 : $datos[0]['shipping_cost'] / 1.16;
            $temporal   = str_replace('{{sinnube_id}}', 'ENVIO', $temporal);
            $temporal   = str_replace('{{products_name}}', 'GASTOS DE ENVIO NACIONAL', $temporal);
            $temporal   = str_replace('{{74}}', '1', $temporal);
            $temporal   = str_replace('{{unidad}}', 'SERVICIO', $temporal);
            $temporal   = str_replace('{{importe}}', Utilerias::redondear($importe), $temporal);
            $temporal   = str_replace('{{montoBaseIVA}}', Utilerias::redondear($importe), $temporal);
            $temporal   = str_replace('{{subtotalDet}}', Utilerias::redondear($importe), $temporal);
            $temporal   = str_replace('{{montoIVA}}', Utilerias::redondear($importe * .16), $temporal);
            $temporal   = str_replace('{{valorUnitario}}', Utilerias::redondear($importe, 3), $temporal);
            $temporal   = str_replace('{{cantidad}}', 1, $temporal);
            $conceptos .= $temporal;
            $subtotal += Utilerias::redondear($importe);
            $iva += Utilerias::redondear($importe * .16);
        }

        $valores = [
            'folioAutofacturacion'  => 'THS' . date('ym') . str_pad($datos[0]['id'] . $datos[0]['orders_id'], 6, "0", STR_PAD_LEFT),
            'subtotal'              => $subtotal,
            'iva'                   => $iva,
            'total'                 => Utilerias::redondear($subtotal + $iva),
        ];

        $conexion->affectedRows("UPDATE orders SET folioAutofacturacion = '" . $valores['folioAutofacturacion'] . "' WHERE orders_id = '{$id_order}';");

        foreach($valores as $key => $valor){
            $xml = str_replace('{{'. $key .'}}', $valor, $xml);
        }

        return str_replace('{{conceptos}}', $conceptos, $xml);
        /* $order=GetModel::getRelDataFilter(); */
    }
}
