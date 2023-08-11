<?php 
namespace App\Controllers\Catalogs;

use App\Controllers\Sinube\SinubeController;
use App\Models\GetModel;
use App\Models\PostModel;
use App\Models\PutModel;
use App\Traits\App;


class CatalogProductsController{

    use App;
    
    static function getProducts(){
        $products=GetModel::getData("catalog_products","*",'id_product_sinube','ASC',null,null);
        return $products;
    }

    static function getProductsById($id_product){
        $products=GetModel::getDataFilter("catalog_products","*",'id_product',$id_product,null,null,null,null);
        return $products;
    }


    static function  asyncProductsBySinube($data_sinube){
        $data_sinube=$data_sinube??SinubeController::consultar();
        if(isset($data_sinube)){
            foreach ($data_sinube as $data){
                $validate=GetModel::getDataFilter("catalog_products","*","id_product_sinube",$data['producto'],null,null,null,null);
                if(empty($validate)){
                    self::storeProductsBySinube($data_sinube);
                }else{
                    self::updateProductsBySinube($data_sinube);
                    
                }
            }
            return "datos actualizados";
        }
        return null;
    }

    static function storeProductsBySinube($products){
        $data=array();
        
        if(isset($products)){
            foreach ($products as $product){
                
                $data=[
                    "id_product_sinube"=>$product['producto'],
                    "description_product"=>$product['descripcion'],
                     "brand_product"=>$product['marca'],
                     "category_product"=>$product['categoria'],
                     "price_sinube"=>$product['precio'],
                     "price_sinube_with_vat"=>$product['precioConIVA'],
                     "price_minimum_sinube"=>$product['precioMinimo'],
                     'existence_product'=>$product['existencia'],
                     'unit_catalog_product'=>$product['unidad'],
                     "created_at"=> self::getCurrentTime()
                ];
                PostModel::postData("catalog_products",$data);
            }
        }
    }
    
    static function updateProductsBySinube($product){
        $data=array();
        
        if(isset($product)){
            foreach ($product as $product){
                
                $data=[
                    "description_product"=>$product['descripcion'],
                     "brand_product"=>$product['marca'],
                     "category_product"=>$product['categoria'],
                     "price_sinube"=>$product['precio'],
                     "price_sinube_with_vat"=>$product['precioConIva'],
                     "price_minimum_sinube"=>$product['precioMinimo'],
                     'existence_product'=>$product['existencia'],
                     'unit_catalog_product'=>$product['unidad'],
                     "updated_at"=> self::getCurrentTime()
                ];
               PutModel::putData("catalog_products",$data,'id_product_sinube', $product['producto']);
            }
        }
    }

}
