<?php

namespace App\Controllers\Orders;

use App\Controllers\Sinube\SinubeController;
use App\Models\GetModel;
use App\Models\PostModel;
use App\Models\PutModel;
use App\Traits\Validate;
use App\Traits\App;
use stdClass;

class OrdersController
{
    use App;
    use Validate;

    static function preOrder($data)
    {
        session_start();
        $total_count = 0;
        $total_productos = 0;
        $total_product_excedent = 0;
        $product_no_exist = [];
        $info_total_order=new stdClass();
        $iva=self::FACTOR_TOTAL_IVA;
        foreach ($data as $key => $value) {

            $id_product = $value->id;
            $cantidad_productos = self::validateNumber($value->cantidadProductos);
            $cantidad_Excendete = self::validateNumber($value->cantidadExcedente);

            $product = GetModel::getDataFilter('catalog_products', 'id_product_sinube,price_sinube', 'id_product', $id_product, null, null, null, null);

            if (empty($product)) {
                $product_no_exist[] = $value;
                continue;
            }

            $total_count += ($product[$key]->price_sinube*$cantidad_productos);
            $total_productos += $cantidad_productos;
            $total_product_excedent += $cantidad_Excendete;
        }
        
        $info_total_order->id_user=$_SESSION['id_user'];     
        $info_total_order->total_count=$total_count;
        $info_total_order->total_productos=$total_productos;
        $info_total_order->total_product_excedent=$total_product_excedent;
        $info_total_order->total_coust_out_vat=self::calculatePriceWithoutIVA($total_count,$iva);
        $info_total_order->vat=self::TASA_IVA;
        print_r(self::storeOrder($info_total_order));
        
    }

    static function storeOrder($data)
    {
        
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
        return $insert;
    }
}
