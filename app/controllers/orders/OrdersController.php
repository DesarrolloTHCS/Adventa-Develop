<?php

namespace App\Controllers\Orders;

use App\Controllers\Sinube\SinubeController;
use App\Models\GetModel;
use App\Models\PostModel;
use App\Models\PutModel;
use App\Traits\Validate;
use App\Traits\App;
use App\Traits\Sinube;
use stdClass;
use Exception;

class OrdersController
{
    use App;
    use Validate;

    /**
     * Author: Alfredo Segura Vara <pixxo2010@gmail.com>
     * Description: Obtienes las ordenes por id del usuario
     * Date: 08-08-2023
     * @param int $id_user
     * @return object
     * 
     */
    static function getOrdersByIdUser($id_user)
    {
        $id_user = $_SESSION['id_user'];
        $orders = GetModel::getRelDataFilter("orders,sales_note", 'id_order,id_order', '*', 'id_user', $id_user, 'DESC', null, null, null);
        return $orders;
    }

    /**
     * Author: Alfredo Segura Vara <pixxo2010@gmail.com>
     * Description: Creas una orden de compra del usuario
     * Date: 08-08-2023
     * @param object $data
     * @return array
     * 
     */
    static function createOrder($data)
    {

        if (empty($data)) {
            $response = [
                "error" => "No se recibieron datos"
            ];
            return $response;
        }

        session_start();
        $total_count = 0;
        $total_productos = 0;
        $total_product_excedent = 0;
        $product_no_exist = [];
        $product_exist = [];
        $info_total_order = new stdClass();
        $iva = self::FACTOR_TOTAL_IVA;

        foreach ($data as $key => $value) {

            $id_product = $value->id;
            $cantidad_productos = self::validateNumber($value->cantidadProductos);
            $cantidad_Excendete = self::validateNumber($value->cantidadExcedente);

            if ($cantidad_productos <= 0) {
                $response = [
                    "id_product" => $id_product,
                    "error" => "Debes agregar al menos un producto"
                ];
                return $response;
            }

            $product = GetModel::getDataFilter('catalog_products', 'id_product_sinube,price_sinube_with_vat,existence_product', 'id_product', $id_product, null, null, null, null);
            if (empty($product)) {
                $product_no_exist[] = $value;
                continue;
            }

            if ($product[0]->existence_product < $cantidad_productos) {
                $response = [
                    "id_product" => $id_product,
                    "error" => "No hay suficiente producto en existencia"
                ];
                return $response;
            }

            $total_count += ($product[0]->price_sinube_with_vat * $cantidad_productos);
            $total_productos += $cantidad_productos;
            $total_product_excedent += $cantidad_Excendete;

            $update = [
                "existence_product" => $product[0]->existence_product - $cantidad_productos
            ];
            $update_catalog_products = PutModel::putData("catalog_products", $update, "id_product", $id_product);
            $product_exist[] = $value;
        }

        $info_total_order->id_user = $_SESSION['id_user'];
        $info_total_order->total_count = $total_count;
        $info_total_order->total_productos = $total_productos;
        $info_total_order->total_product_excedent = $total_product_excedent;
        $info_total_order->total_coust_out_vat = self::calculatePriceWithoutIVA($total_count, $iva);
        $info_total_order->vat = self::TASA_IVA;
        $order = self::storeOrder($info_total_order);

        foreach ($product_exist as $key => $value) {
            $store = [
                "id_order" => $order['lastId'],
                "id_product" => $value->id,
                "quantity_products_order" => $value->cantidadProductos,
                "quantity_excedent_products_order" => $value->cantidadExcedente,
                "created_at" => App::getCurrentTime(),
            ];

            $insert = PostModel::postData("products_order", $store);
        }
        $sales_note = self::salesNote($order['lastId']);
        if (isset($sale_note['latId'])) {
            $result = [
                "status" => "success",
                "message" => "Orden de compra creada correctamente"
            ];
            return $result;
        } else {
            $result = [
                "status" => "error",
                "message" => "Error al crear la nota de venta"
            ];
            return $result;
        }
    }

    /**
     * Author: Alfredo Segura Vara <pixxo2010@gmail.com>
     * Description: Almacena la orden de compra del usuario
     * Date: 08-08-2023
     * @param object $data
     * @return array
     * 
     */
    static function storeOrder($data)
    {
        try {
            $store = [
                "id_user" => $data->id_user,
                "quantity_products_order" => $data->total_productos,
                "total_coust_order" => $data->total_count,
                "total_coust_out_vat_order" => $data->total_coust_out_vat,
                "vat_order" => $data->vat,
                "status_order" => 0,
                "created_at" => App::getCurrentTime(),
            ];

            $insert = PostModel::postData("orders", $store);

            $id_order = $insert['lastId'];

            $folio = Sinube::folioAutofacturacion($id_order);

            $insert_folio = PutModel::putData("orders", ["folio_auto_facturacion" => $folio], "id_order", $id_order);


            return $insert;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }


    static function salesNote($id_order)
    {
        $store = "CAPITAL SAPI";

        $descuento = 0;

        $sale_note = json_decode(SinubeController::createSalesNote($id_order, $store));

        $store = [
            "id_order" => $id_order,
            "folio_sale_note" => $sale_note->folio,
            "report_sale_note" => $sale_note->pdf,
            "process_type_sale_note" => $sale_note->TipoProceso,
            "created_at" => $sale_note->fechaNotaVenta
        ];
        $insert = PostModel::postData("sales_note", $store);
        return $insert;
    }
}
