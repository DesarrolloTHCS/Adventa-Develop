<?php
namespace App\Traits;

Trait Log
{
    static public function log($data)
    {        
      $log=[
        'id_user'=>$result['lastId'],
        'type'=>'new register'
      ];

      $log_insert=PostModel::postData('log_insert',[
        'tablename'=>'users',
        'idtable'=>$result['lastId'],
        'query'=>json_encode($log),
        'id_user_reg'=>$result['lastId']
      ]);
    }
}


