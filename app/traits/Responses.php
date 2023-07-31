<?php
namespace App\Traits;

Trait Responses{
static public function response($response)
    {
      if (!empty($response)) {
        $json = array(
          "status" => 200,
          /* "total" => count($response), */
          'result' => $response
  
        );
        echo json_encode($json, http_response_code($json["status"]));
      } else {
        $json = array(
          "status" => 404,
          'result' => $response  
        );
        echo json_encode($json, http_response_code($json["status"]));
      }
    }
}