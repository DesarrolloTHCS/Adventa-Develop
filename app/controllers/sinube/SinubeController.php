<?php

namespace App\Controllers\Sinube;

use App\Controllers\Catalogs\CatalogProductsCOntroller;
use App\Models\GetModel;
use App\Traits\Sinube;
use App\Traits\Validate;
use Exception;
use SimpleXMLElement;
use stdClass;

class SinubeController
{
    use Sinube;
    use Validate;

    const URL_GETPOST_SINUBE = "http://getpost.si-nube.appspot.com/getpost";

    const URL_BLOB_SINUBE = "http://ep-dot-si-nube.appspot.com/blob?par=";

    static function consultar($store = "CAPITAL SAPI", $price_list = "Adventa", $cursor = null)
    {
        try {

            $urlf = self::URL_GETPOST_SINUBE;
            $cursor = null;
            $products = array();
            $empresa    = 'DOD021211S63';
            $usuario    = 'contabilidad.diodi10@gmail.com';
            $password   = 'KEPF2R3E';
            $cursor     = ($cursor == null) ? '' : " CURSOR {$cursor}";
            $consulta   = "SELECT P.descripcion, P.marca, P.codigoAuxiliar,P.unidad, L.empresa, L.existencia, L.producto, L.sucursal, P.activo, L.almacen, PP.precio, PP.precioMinimo FROM DbProducto AS P INNER JOIN DbInvProductoLote AS L ON L.producto = P.producto AND L.empresa = P.empresa INNER JOIN DbProductoPrecio AS PP ON PP.producto = P.producto AND PP.empresa = P.empresa WHERE PP.listaPrecios = '" . $price_list . "' AND P.empresa = 'DOD021211S63' AND L.almacen= '" . $store . "' AND PP.listaPrecios = '" . $price_list . "'{$cursor}";

            /* 
             */

            //$urlf       = "http://getpost.facturanube.appspot.com/getpost";   

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
                $products[] = [/*  */
                    'descripcion'   => $linea_re[0],
                    'marca'           => $linea_re[1] ?? "sin marca",
                    'categoria'   => $linea_re[2],
                    'unidad'       => $linea_re[3] == 'PIEZA' ? 'PZA' : $linea_re[3],
                    'empresa'      => $linea_re[4],
                    'existencia'      => $linea_re[5],
                    'producto'      => $linea_re[6],
                    'precio'        => $linea_re[10],
                    'precioConIva'  => self::calculatePriceWithIVA($linea_re[10], self::PORCENTAJE_IVA),
                    'precioMinimo'    => $linea_re[11] ?? 0


                ];
            }
            $sync = CatalogProductsCOntroller::asyncProductsBySinube($products);

            return $products;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    static function createSalesNote(int $id_order, string $store)
    {
        try {

            $urlf = self::URL_BLOB_SINUBE;
            $id_order = $id_order;
            $order = GetModel::getRelDataFilter('orders,products_order', 'id_order,id_order', '*', 'id_order', $id_order, null, null, null, null);
            $products = GetModel::getRelDataFilter('products_order,catalog_products', 'id_catalog_product,id_product', 'quantity_products_order,id_product_sinube,description_product,brand_product,category_product,price_sinube,price_minimum_sinube,existence_product,
        unit_catalog_product', 'id_order', $id_order, null, null, null, null);

            $user = GetModel::getRelDataFilter('orders,users', 'id_user,id_user', 'name_user,id_company,paternal_surname_user,maternal_surname_user,email_user,person_type_user', 'id_order', $id_order, null, null, null, null);

            $user_company = GetModel::getDataFilter('companies', '*', 'id_company', $user[0]->id_company, null, null, null, null);

            $emisor_company = GetModel::getDataFilter('companies', '*', 'id_company', 1, null, null, null, null);

            $store = $store;
            $sucursal = "Matriz";
            $forma_de_pago = GetModel::getRelDataFilter('orders,catalog_payment_methods', 'id_catalog_payment_method,id_catalog_payment_method', 'codigo_catalog_payment_methods,name_catalog_payment_method', 'id_order', $id_order, null, null, null, null);
            $permiteAgregarProductosNoInv = 0;
            $comprobante = new stdClass();
            $receptor = new stdClass();

            $comprobante->rfcEmisor = $emisor_company[0]->rfc_company;
            $comprobante->sistema = "TuHogarConSentido"/* $emisor_company[0]->razon_social_company */;
            $comprobante->almacen = $store;
            $comprobante->sucursal = $sucursal;
            $comprobante->folioAutofacturacion = $order[0]->folio_auto_facturacion;
            $comprobante->permiteAgregarProductosNoInv = $permiteAgregarProductosNoInv;
            $comprobante->observacion = $order[0]->observation_order;
            $comprobante->formaDePago = $forma_de_pago[0]->codigo_catalog_payment_methods;
            $comprobante->subtotal = $order[0]->total_coust_out_vat_order;
            $comprobante->referencia = "Prueba de pedido";
            $comprobante->porcentajeIVA = ($order[0]->vat_order * 100);
            $comprobante->descuento = $order[0]->discount_order;
            $comprobante->montoIVA = ($order[0]->total_coust_order - $order[0]->total_coust_out_vat_order);
            $comprobante->total = $order[0]->total_coust_order;
            $comprobante->difZonaHoraria = self::DIFERENCIA_HORARIA;
            $comprobante->monedaSinube = self::MONEDA_SINUBE;

            if ($user[0]->person_type_user == 0) {
                $receptor->rfc = $user_company[0]->rfc_company;
                $receptor->razonSocial = $user_company[0]->razon_social_company;
                $receptor->esPersonaFisica = "0";
            }
            $xml = '<?xml version="1.0" encoding="utf-8"?>';
            $xml .= <<<XML
        
        <Comprobante rfcEmisor="{$comprobante->rfcEmisor}" sistema="{$comprobante->sistema}" almacen="{$comprobante->almacen}" sucursal="{$comprobante->sucursal}" codigoReporte="NOTA DE VENTA-DIODI" folioAutofacturacion="{$comprobante->folioAutofacturacion}" permiteAgregarProductosNoInv="{$comprobante->permiteAgregarProductosNoInv}" observacion="ID DEL PEDIDO # {$comprobante->observacion}" referencia="{$comprobante->referencia}" formaDePago="{$comprobante->formaDePago}" descuento="{$comprobante->descuento}" subtotal="{$comprobante->subtotal}" montoIVA="{$comprobante->montoIVA}" porcentajeIVA="{$comprobante->porcentajeIVA}" monedaSinube="{$comprobante->monedaSinube}" total="{$comprobante->total}" difZonaHoraria="{$comprobante->difZonaHoraria}">
          <Receptor rfc="{$receptor->rfc}" razonSocial="{$receptor->razonSocial}" esPersonaFisica="{$receptor->esPersonaFisica}"/>
          <Conceptos>
          
XML;

            foreach ($products as $product) {
                $descuento = 0;
                $monto_sin_iva = $product->price_sinube * $product->quantity_products_order;
                $montoBaseIVA = self::calculatePriceWithIVA($monto_sin_iva, ($order[0]->vat_order * 100));
                $montoIVA = round(($monto_sin_iva * .16), 2);
                $subtotalDet = $monto_sin_iva - $descuento;

                $xml .= <<<XML
            <Concepto productoSinube="{$product->id_product_sinube}" descripcion="{$product->description_product}" cantidad="{$product->quantity_products_order}" unidadSinube="{$product->unit_catalog_product}" valorUnitario="{$product->price_sinube}" descuento="0" tipoIVA="Causa IVA" montoBaseIVA="{$monto_sin_iva}" montoIVA="{$montoIVA}" importe="{$monto_sin_iva}" subtotalDet="{$subtotalDet}"/>
XML;
            }
            $xml .= '</Conceptos></Comprobante>';

            $par = 'tipo=7
emp=DOD021211S63
suc=Matriz
usu=contabilidad.diodi10@gmail.com
pwd=KEPF2R3E';

            $par64 = base64_encode($par);
            $url = $urlf . $par64;
            $headers = [
                "Content-type: text/xml", "Content-length: " . strlen($xml), "Connection: close",
            ];
            //Inicio de método de conexión al POST
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url); //Dependiendo si es SiNube o FacturaNube se asigna la URL
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);
            //Impresión del resultado
            $result = curl_exec($ch);

            if (curl_errno($ch)) {
                error_log('cURL error al intentar conectarse a ' . $url . ': ' . curl_error($ch));
            }

            curl_close($ch);
            $xml_info = new SimpleXMLElement($result);
            $xml_info = json_encode($xml_info);
            return $xml_info;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
