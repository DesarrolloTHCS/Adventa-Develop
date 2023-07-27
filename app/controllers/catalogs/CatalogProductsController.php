<?php 
namespace App\Controllers\Catalogs;

class CatalogProductsCOntroller{

    static function updateCatalogProducts($products){

        
        if(isset($products)){
            foreach ($products as $product){
                $data=[
                    'id_product_type','id_category','id_subcategory','id_brand','id_unit','id_p
                ];
            }
        }
    }
}
?>