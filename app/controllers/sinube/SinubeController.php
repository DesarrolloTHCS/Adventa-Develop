<?php

namespace App\Controllers\Sinube;

use App\Controllers\Catalogs\CatalogProductsCOntroller;
use App\Models\GetModel;
use stdClass;
use XMLParser;

class SinubeController
{

    const URL_GETPOST_SINUBE = "http://getpost.si-nube.appspot.com/getpost";

    const URL_BLOB_SINUBE = "http://ep-dot-si-nube.appspot.com/blob?par=";

    static function consultar($store = "CAPITAL SAPI", $price_list = "Adventa", $cursor = null)
    {

        //Conta1500#
        $cursor = null;
        $products = array();
        $empresa    = 'DOD021211S63';
        $usuario    = 'contabilidad.diodi10@gmail.com';
        $password   = 'KEPF2R3E';
        $cursor     = ($cursor == null) ? '' : " CURSOR {$cursor}";
        $consulta   = "SELECT P.descripcion, P.marca, P.codigoAuxiliar,P.unidad, L.empresa, L.existencia, L.producto, L.sucursal, P.activo, L.almacen, PP.precio, PP.precioMinimo FROM DbProducto AS P INNER JOIN DbInvProductoLote AS L ON L.producto = P.producto AND L.empresa = P.empresa INNER JOIN DbProductoPrecio AS PP ON PP.producto = P.producto AND PP.empresa = P.empresa WHERE PP.listaPrecios = '" . $price_list . "' AND P.empresa = 'DOD021211S63' AND P.activo = true AND L.almacen= '" . $store . "' AND PP.listaPrecios = '" . $price_list . "'{$cursor}";

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
                'unidad'       => $linea_re[3]=='PIEZA'? 'PZA':$linea_re[3],
                'empresa'      => $linea_re[4],
                'existencia'      => $linea_re[5],
                'producto'      => $linea_re[6],
                'precio'        => $linea_re[10],
                'precioMinimo'    => $linea_re[11] ?? 0


            ];
        }
        $sync = CatalogProductsCOntroller::asyncProductsBySinube($products);
        
        return $products;
    }

    static function createSalesNote(int $id_order = null, string $store = null, float $descuento = null)
    {
        $id_order = $id_order;
        /* $order=GetModel::getRelDataFilter('orders,products_order','id_order,id_order','*','id_order',$id_order,null,null,null,null);
         */
        $products = GetModel::getRelDataFilter('products_order,catalog_products', 'id_catalog_product,id_product', '*', 'id_order', $id_order, null, null, null, null);
        $store = $store;
        $urlf = self::URL_BLOB_SINUBE;
        $conceptos = '';
        $subtotal = 0.0;
        $iva = 0.0;
        $comprobante = new stdClass();
        $receptor = new stdClass();
        $onceptos = new stdClass();

        $comprobante->rfcEmisor = "";
        $comprobante->sistema = "";
        $comprobante->almacen = "";
        $comprobante->sucursal = "";
        $comprobante->folioAutofacturacion = "";
        $comprobante->permiteAgregarProductosNoInv = "";
        $comprobante->observacion = "";
        $comprobante->formaDePago = "";
        $comprobante->subtotal = "";
        $comprobante->referencia = "";
        $comprobante->porcentajeIVA = "";
        $comprobante->descuento = "";
        $comprobante->montoIVA = "";
        $comprobante->desglosaIEPS = "";
        $comprobante->total = "";
        $comprobante->montoIEPS = "";
        $comprobante->difZonaHoraria = "";
        $comprobante->monedaSinube = "";

        $receptor->cliente = "";
        $receptor->rfc = "";
        $receptor->razonSocial = "";
        $receptor->esPersonaFisica = "";


        
        /* $mensaje = "ENVIO GRATIS";
        if ($datos[0]['shipping_cost'] > 0) {
            $mensaje = "ENVIO CON COSTO";
        } */

        /* <Comprobante 
        rfcEmisor="DOD021211S63" 
        sistema="TuHogarConSentido" 
        almacen="' . $store . '"
        sucursal="Matriz" 
        folioAutofacturacion="{{folioAutofacturacion}}" 
        permiteAgregarProductosNoInv="0"
        observacion="ID DEL PEDIDO # ' . ($datos[0]['is_b2b'] ? 'B' : '') . $id_order . '" 
        formaDePago="03"
        subtotal="{{subtotal}}" 
        referencia="E-COMMERCE ' . $mensaje . '" 
        porcentajeIVA="16" 
        descuento="0" 
        montoIVA="{{iva}}" 
        desglosaIEPS="0" 
        total="{{total}}" 
        montoIEPS="0" 
        difZonaHoraria="-6"
        monedaSinube="MXN" 
        > */
        $xml = <<<XML
        <?xml version="1.0" encoding="utf-8"?>

        <Comprobante 
        rfcEmisor="{$comprobante->rfcEmisor}" 
        sistema="{$comprobante->sistema}" 
        almacen="{$comprobante->almacen}"
        sucursal="{$comprobante->sucursal}" 
        folioAutofacturacion="{$comprobante->folioAutofacturacion}" 
        permiteAgregarProductosNoInv="{$comprobante->permiteAgregarProductosNoInv}"
        observacion="ID DEL PEDIDO # {$comprobante->observacion}" 
        formaDePago="{$comprobante->formaDePago}"
        subtotal="{$comprobante->subtotal}" 
        referencia="{$comprobante->referencia}" 
        porcentajeIVA="{$comprobante->porcentajeIVA}" 
        descuento="{$comprobante->descuento}" 
        montoIVA="{$comprobante->montoIVA}" 
        desglosaIEPS="{$comprobante->desglosaIEPS}" 
        total="{$comprobante->total}" 
        montoIEPS="{$comprobante->montoIEPS}" 
        difZonaHoraria="{$comprobante->difZonaHoraria}"
        monedaSinube="{$comprobante->monedaSinube}" 
        >

          <Receptor 
          cliente= "{$receptor->cliente}" 
          rfc="$receptor->rfc" 
          razonSocial="$receptor->razonSocial"
          esPersonaFisica="$receptor->esPersonaFisica"
          />
          <Conceptos>
          
          </Conceptos>
        </Comprobante>
XML;
        print($xml);
        return;
        $concepto = '<Concepto 
        productoSinube="{{sinnube_id}}" 
        descripcion="{{products_name}}" 
        cantidad="{{cantidad}}" 
        unidadSinube="{{unidad}}"
        valorUnitario="{{valorUnitario}}" 
        descuento="0" 
        tipoIVA="Causa IVA" 
        montoBaseIVA="{{montoBaseIVA}}" 
        montoIVA="{{montoIVA}}"
        importe="{{importe}}" 
        subtotalDet="{{subtotalDet}}"/>';

        $productos = 0;
        $productos_para_descuento = 0;
        if ($datos[0]['shipping_cost'] > 0) {
            $productos++;
        }
        foreach ($datos as $linea) {
            $productos++;
            $productos_para_descuento++;
        }
        $descuento_prod = $descuento / $productos_para_descuento;

        foreach ($datos as $linea) {
            $temporal = $concepto;
            foreach ($linea as $key => $valor) {
                $temporal = str_replace('{{' . $key . '}}', htmlspecialchars($valor), $temporal);
            }
            //$importe    = $linea['final_price'] / 1.16;
            $importe    = $descuento > 0 ? ($linea['final_price'] - $descuento_prod) / 1.16 : $linea['final_price'] / 1.16;
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

        if ($datos[0]['shipping_cost'] > 0) {
            $temporal = $concepto;
            //$importe = $datos[0]['shipping_cost'] / 1.16;
            $importe = $descuento > 0 ? ($datos[0]['shipping_cost']) / 1.16 : $datos[0]['shipping_cost'] / 1.16;
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

        foreach ($valores as $key => $valor) {
            $xml = str_replace('{{' . $key . '}}', $valor, $xml);
        }

        return str_replace('{{conceptos}}', $conceptos, $xml);
        /* $order=GetModel::getRelDataFilter(); */
    }
}
