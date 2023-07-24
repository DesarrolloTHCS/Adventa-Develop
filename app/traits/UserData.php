<?php

namespace App\Traits;

use Exception;

trait UserData
{

    static public function setIdUser()
    {
        try {
            $idUser = $_SESSION['id_user']??NULL;
            return $idUser;
        } catch (Exception $e) {
            
        }
    }
}
