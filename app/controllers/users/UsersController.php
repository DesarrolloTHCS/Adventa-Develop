<?php
namespace App\Controllers\Users;

use App\Models\GetModel;

class UsersController{

static function getAddresses($id){
  $data=GetModel::getDataFilter('addresses','id_user','id_user',$id,null,null,null,null);
  return $data;
    
    }
}
?>