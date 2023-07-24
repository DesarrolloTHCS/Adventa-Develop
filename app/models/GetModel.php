<?php
namespace App\Models;

use App\Models\Connection;
use PDO;
use PDOException;


class GetModel
{
  /*
  Selecciona sin filtro
  */
  static public function getData($table, $select, $orderBy, $orderMode, $startAt, $endAt)
  {
    $selectArray = explode(",", $select);
    /*Validar existencia de la base de datos*/
    if (empty(Connection::getColumnsData($table, $selectArray))) {
      return null;
    } else {
      /*Trae datos sin ordernar y sin limites*/
      $sql = "SELECT $select FROM $table";

      if ($orderBy != null && $orderMode != null && $startAt == null && $endAt == null) {
        /*Trae datos ordenadas y sin limites*/
        $sql = "SELECT $select FROM $table ORDER BY $orderBy $orderMode";
      } else if ($orderBy != null && $orderMode != null && $startAt != null && $endAt != null) {
        /*Trae datos ordenadas y limitados*/
        $sql = "SELECT $select FROM $table ORDER BY $orderBy $orderMode LIMIT $startAt, $endAt";
      } else if ($orderBy == null && $orderMode == null && $startAt != null && $endAt != null) {
        /*Trae datos limitados sin ordenar*/
        $sql = "SELECT $select FROM $table LIMIT $startAt, $endAt";
      }
      $stm = Connection::connect()->prepare($sql);
      try {
        $stm->execute();
      } catch (PDOException $error) {
        return null;
      }
      $result = $stm->fetchAll(PDO::FETCH_CLASS);
      return $result;
    }
  }
  /*
  Selecciona los datos con filtro
  */
  static public function getDataFilter($table, $select, $linkTo, $equalTo, $orderBy, $orderMode, $startAt, $endAt)
  {
    $linkToArray = explode(',', $linkTo);
    $selectArray = explode(",", $select);
    foreach ($linkToArray as $key => $value) {
      array_push($selectArray, $value);
    }
    $selectArray = array_unique($selectArray);
    /*Validar existencia de la base de datos*/
    if (empty(Connection::getColumnsData($table, $selectArray))) {
      return null;
    } else {

      $equalToArray = explode(',', $equalTo);
      $linkToText = "";
      if (count($linkToArray) > 1) {
        foreach ($linkToArray as $key => $value) {
          if ($key > 0) {
            $linkToText .= "AND " . $value . "= :" . $value . " ";
          }
        }
      }

      /*=========================
    Trae datos sin ordernar y sin limites
    ===========================*/
      $sql = "SELECT $select FROM $table WHERE $linkToArray[0]=:$linkToArray[0] $linkToText";
      if ($orderBy != null && $orderMode != null && $startAt == null && $endAt == null) {
        /*=========================
    Trae datos ordenadas y sin limites
    ===========================*/
        $sql = "SELECT $select FROM $table WHERE $linkToArray[0]=:$linkToArray[0] $linkToText ORDER BY $orderBy $orderMode";
      } else if ($orderBy != null && $orderMode != null && $startAt != null && $endAt != null) {
        /*=========================
    Trae datos ordenadas y limitados
    ===========================*/
        $sql = "SELECT $select FROM $table WHERE $linkToArray[0]=:$linkToArray[0] $linkToText ORDER BY $orderBy $orderMode LIMIT $startAt,$endAt";
      } else if ($orderBy == null && $orderMode == null && $startAt != null && $endAt != null) {
        /*=========================
    Trae datos limitados sin ordenar
    ===========================*/
        $sql = "SELECT $select FROM $table WHERE $linkToArray[0]=:$linkToArray[0] $linkToText LIMIT $startAt,$endAt";
      }
      $stm = Connection::connect()->prepare($sql);
      foreach ($linkToArray as $key => $value) {
        $stm->bindParam(":" . $value, $equalToArray[$key], PDO::PARAM_STR);
      }
      try {
        $stm->execute();
      } catch (PDOException $error) {
        return null; 
      }
      $result = $stm->fetchAll(PDO::FETCH_CLASS);
      return $result;
    }
  }
  /*
  Selecciona los datos sin filtro de tablas relacionadas
  */
  static public function getRelData($rel, $type, $select, $orderBy, $orderMode, $startAt, $endAt)
  {
    $relArray = explode(",", $rel);
    $typeArray = explode(",", $type);
    $InnerJoinText = "";
    if (count($relArray) > 1) {
      foreach ($relArray as $key => $value) {

        /*Validar existencia de la base de datos*/
        if (empty(Connection::getColumnsData($value, ["*"]))) {
          return null;
        }

        if ($key > 0) {
        /*   $InnerJoinText .= "INNER JOIN " . $value . " ON " . $relArray[0] . ".id_" . $typeArray[$key] . "_" . $typeArray[0] . " = " . $value . ".id_" . $typeArray[$key] . " "; */
        $InnerJoinText .= "INNER JOIN " . $value . " ON " . $relArray[0] . "." . $typeArray[$key] ." = " . $value . "." . $typeArray[$key] . " ";
        }
      }

      /*Trae datos sin ordernar y sin limites*/
      $sql = "SELECT $select FROM $relArray[0] $InnerJoinText";

      if ($orderBy != null && $orderMode != null && $startAt == null && $endAt == null) {

        /*Trae datos ordenadas y sin limites*/
        $sql = "SELECT $select FROM $relArray[0] $InnerJoinText ORDER BY $orderBy $orderMode";
      } else if ($orderBy != null && $orderMode != null && $startAt != null && $endAt != null) {

        /*Trae datos ordenadas y limitados*/
        $sql = "SELECT $select FROM $relArray[0] $InnerJoinText ORDER BY $orderBy $orderMode LIMIT $startAt, $endAt";
      } else if ($orderBy == null && $orderMode == null && $startAt != null && $endAt != null) {

        /*Trae datos limitados sin ordenar*/
        $sql = "SELECT $select FROM $relArray[0] $InnerJoinText LIMIT $startAt, $endAt";
      }

      $stm = Connection::connect()->prepare($sql);
      try {
        $stm->execute();
      } catch (PDOException $error) {
        return null;
      }
      $result = $stm->fetchAll(PDO::FETCH_CLASS);
      return $result;
    } else {
      return null;
    }
  }

  /*
  Selecciona los datos con filtro de tablas relacionadas
  */
  static public function getRelDataFilter($rel, $type, $select, $linkTo, $equalTo, $orderBy, $orderMode, $startAt, $endAt)
  {
    $relArray = explode(",", $rel);
    $typeArray = explode(",", $type);
    $linkToArray = explode(',', $linkTo);
    $equalToArray = explode(',', $equalTo);
    $InnerJoinText = "";
    $linkToText = "";

    
    if (count($relArray) > 1) {
      foreach ($relArray as $key => $value) {
        /*Validar existencia de la base de datos*/
        if (empty(Connection::getColumnsData($value, ["*"]))) {
          
          return null;
        }
        if ($key > 0) {
          $InnerJoinText .= "INNER JOIN " . $value . " ON " . $relArray[0] . "." . $typeArray[$key]. " = " . $value . "." . $typeArray[$key] . " ";
        }
      }
      
      if (count($linkToArray) > 1) {
        foreach ($linkToArray as $key => $value) {
          if ($key > 0) {
            $linkToText .= "AND " . $value . "= :" . $value . " ";
          }
        }
      }
        /*Trae datos sin ordernar y sin limites*/
        $sql = "SELECT $select FROM $relArray[0] $InnerJoinText WHERE $relArray[0].$linkToArray[0]=:$linkToArray[0] $linkToText ";

        if ($orderBy != null && $orderMode != null && $startAt == null && $endAt == null) {
          /*Trae datos ordenadas y sin limites*/
          $sql = "SELECT $select FROM $relArray[0] $InnerJoinText WHERE $relArray[0].$linkToArray[0]=:$linkToArray[0] $linkToText ORDER BY $orderBy $orderMode";
        } else if ($orderBy != null && $orderMode != null && $startAt != null && $endAt != null) {
          /*Trae datos ordenadas y limitados*/
          $sql = "SELECT $select FROM $relArray[0] $InnerJoinText WHERE $relArray[0].$linkToArray[0]=:$linkToArray[0] $linkToText ORDER BY $orderBy $orderMode LIMIT $startAt, $endAt";
        } else if ($orderBy == null && $orderMode == null && $startAt != null && $endAt != null) {
          /*Trae datos limitados sin ordenar*/
          $sql = "SELECT $select FROM $relArray[0] $InnerJoinText WHERE $relArray[0].$linkToArray[0]=:$linkToArray[0] $linkToText LIMIT $startAt, $endAt";
        }
        $stm = Connection::connect()->prepare($sql);
        
        foreach ($linkToArray as $key => $value) {
          
          $stm->bindParam(":" . $value, $equalToArray[$key], PDO::PARAM_STR);
        }
        try {
          $stm->execute();
        } catch (PDOException $error) {
          return $error->getMessage(); 
        }
        $result = $stm->fetchAll(PDO::FETCH_CLASS);
        return $result;
      } else {
        return null;
      }
  }
  /*
  Busca datos sin filtros y sin relaciones
  */
  static public function getDataSearch($table, $select, $linkTo, $search, $orderBy, $orderMode, $startAt, $endAt)
  {
    $linkToArray = explode(',', $linkTo);
    $searchToArray = explode('_', $search);
    $selectArray = explode(",", $select);
    $linkToText = "";
    foreach ($linkToArray as $key => $value) {
      array_push($selectArray, $value);
    }
    $selectArray = array_unique($selectArray);
    if (empty(Connection::getColumnsData($table, $selectArray))) {
      return null;
    } else {
      if (count($linkToArray) > 1) {
        foreach ($linkToArray as $key => $value) {
          if ($key > 0) {
            $linkToText .= "AND " . $value . "= :" . $value . " ";
          }
        }

        /*Trae datos sin ordernar y sin limites*/
        $sql = "SELECT $select FROM $table WHERE $linkToArray[0] LIKE '%$searchToArray[0]%' $linkToText";

        if ($orderBy != null && $orderMode != null && $startAt == null && $endAt == null) {
          /*Trae datos ordenadas y sin limites*/
          $sql = "SELECT $select FROM $table WHERE $$linkToArray[0] LIKE '%$searchToArray[0]%' $linkToText ORDER BY $orderBy $orderMode";
        } else if ($orderBy != null && $orderMode != null && $startAt != null && $endAt != null) {
          /*Trae datos ordenadas y limitados*/
          $sql = "SELECT $select FROM $table WHERE $linkToArray[0] LIKE '%$searchToArray[0]%' $linkToText ORDER BY $orderBy $orderMode LIMIT $startAt, $endAt";
        } else if ($orderBy == null && $orderMode == null && $startAt != null && $endAt != null) {
          /*Trae datos limitados sin ordenar*/
          $sql = "SELECT $select FROM $table WHERE $linkToArray[0] LIKE '%$searchToArray[0]%' $linkToText LIMIT $startAt, $endAt";
        }
        $stm = Connection::connect()->prepare($sql);
        foreach ($linkToArray as $key => $value) {
          if ($key > 0) {
            $stm->bindParam(":" . $value, $searchToArray[$key], PDO::PARAM_STR);
          }
        }
        try {
          $stm->execute();
        } catch (PDOException $error) {
          return null; 
        }
        $result = $stm->fetchAll(PDO::FETCH_CLASS);
        return $result;
      } else {
        return null;
      }
    }
  }
  /*
  Busca los datos con filtro de tablas y relacionadas
  */
  static public function getRelDataSearch($rel, $type, $select, $linkTo, $search, $orderBy, $orderMode, $startAt, $endAt)
  {
    $relArray = explode(",", $rel);
    $typeArray = explode(",", $type);
    $linkToArray = explode(',', $linkTo);
    $searchToArray = explode('_', $search);
    $InnerJoinText = "";
    $linkToText = "";
    if (count($relArray) > 1) {
      foreach ($relArray as $key => $value) {
        if (empty(Connection::getColumnsData($value, ["*"]))) {
          return null;
        }
        if ($key > 0) {
          $InnerJoinText .= "INNER JOIN " . $value . " ON " . $relArray[0] . ".id_" . $typeArray[$key] . "_" . $typeArray[0] . " = " . $value . ".id_" . $typeArray[$key] . " ";
        }
      }
      if (count($linkToArray) > 1) {
        foreach ($linkToArray as $key => $value) {
          if ($key > 0) {
            $linkToText .= "AND " . $value . "= :" . $value . " ";
          }
        }
      }
      /*Trae datos sin ordernar y sin limites*/
      $sql = "SELECT $select FROM $relArray[0] $InnerJoinText WHERE $linkToArray[0] LIKE '%$searchToArray[0]%' $linkToText";

      if ($orderBy != null && $orderMode != null && $startAt == null && $endAt == null) {
        /*Trae datos ordenadas y sin limites*/
        $sql = "SELECT $select FROM $relArray[0] $InnerJoinText WHERE $linkToArray[0] LIKE '%$searchToArray[0]%' $linkToText ORDER BY $orderBy $orderMode";
      } else if ($orderBy != null && $orderMode != null && $startAt != null && $endAt != null) {
        /*Trae datos ordenadas y limitados*/
        $sql = "SELECT $select FROM $relArray[0] $InnerJoinText WHERE $linkToArray[0] LIKE '%$searchToArray[0]%' $linkToText ORDER BY $orderBy $orderMode LIMIT $startAt, $endAt";
      } else if ($orderBy == null && $orderMode == null && $startAt != null && $endAt != null) {
        /*Trae datos limitados sin ordenar*/
        $sql = "SELECT $select FROM $relArray[0] $InnerJoinText WHERE $linkToArray[0] LIKE '%$searchToArray[0]%' $linkToText LIMIT $startAt, $endAt";
      }
      $stm = Connection::connect()->prepare($sql);
      foreach ($linkToArray as $key => $value) {
        if ($key > 0) {
          $stm->bindParam(":" . $value, $searchToArray[$key], PDO::PARAM_STR);
        }
      }
      try {
        $stm->execute();
      } catch (PDOException $error) {
        return null; 
      }
      $result = $stm->fetchAll(PDO::FETCH_CLASS);
      return $result;
    } else {
      return null;
    }
  }

  /*
  Busca todos los datos sin filtros y sin relaciones
  */
  static public function getDataSearchAll($table, $select, $linkTo, $search, $orderBy, $orderMode, $startAt, $endAt)
  {
    $linkToArray = explode(',', $linkTo);
    $searchToArray = explode('_', $search);
    $selectArray = explode(",", $select);
    $linkToText = "";
  
    foreach ($linkToArray as $key => $value) {
      array_push($selectArray, $value);
    }
    
    $selectArray = array_unique($selectArray);
    
    
    if (empty(Connection::getColumnsData($table, $selectArray))) {  
      return null;
    } else {
      
      if (count($linkToArray) > 1) {
        
        foreach ($linkToArray as $key => $value) {
          if ($key > 0) {
            $bind=":$value";
            $linkToText .= "OR " . $value . " LIKE '%". $searchToArray[0] ."%'" . " ";                      
            
          }
        }
      }
      
        /*Trae datos sin ordernar y sin limites*/
        $sql = "SELECT $select FROM $table WHERE $linkToArray[0] LIKE '%$searchToArray[0]%' $linkToText ";
        
        
        if ($orderBy != null && $orderMode != null && $startAt == null && $endAt == null) {
          /*Trae datos ordenadas y sin limites*/
          $sql = "SELECT $select FROM $table WHERE $linkToArray[0] LIKE '%$searchToArray[0]%' $linkToText ORDER BY $orderBy $orderMode";
        } else if ($orderBy != null && $orderMode != null && $startAt != null && $endAt != null) {
          /*Trae datos ordenadas y limitados*/
          $sql = "SELECT $select FROM $table WHERE $linkToArray[0] LIKE '%$searchToArray[0]%' $linkToText ORDER BY $orderBy $orderMode LIMIT $startAt, $endAt";
        } else if ($orderBy == null && $orderMode == null && $startAt != null && $endAt != null) {
          /*Trae datos limitados sin ordenar*/
          $sql = "SELECT $select FROM $table WHERE $linkToArray[0] LIKE '%$searchToArray[0]%' $linkToText LIMIT $startAt, $endAt";
        }
        
        $stm = Connection::connect()->prepare($sql);
        
      /*   foreach ($linkToArray as $key => $value) {
          if ($key > 0) {              
            $value=":$value";
            $stm->bindParam($value, $searchToArray[0], PDO::PARAM_STR);          
        }
      } */
        try {
          $stm->execute();
          
        } catch (PDOException $error) {
          return $error->getMessage(); 
        }
        $result = $stm->fetchAll(PDO::FETCH_CLASS);
        
        return $result; 
    }
  }
  
  /*
  Obtiene los datos por rango
  */
  static public function getDataRange($table, $select, $linkTo, $betweenMin, $betweenMax, $orderBy, $orderMode, $startAt, $endAt, $filterTo, $inTo)
  {
    $selectArray = explode(',', $select);
    $linkToArray = explode(',', $linkTo);
    if($filterTo!=null){
    $filterArray = explode(',', $filterTo);
  }else{
    $filterArray=array();
  }
    foreach ($linkToArray as $key => $value) {
      array_push($selectArray, $value);
    }
    foreach ($filterArray as $key => $value) {
      array_push($selectArray, $value);
    }
    $selectArray = array_unique($selectArray);
    if (empty(Connection::getColumnsData($table, $selectArray))) {
      return null;
    } else {
      $filter = "";
      if ($filterTo != "") {
        $filter = "AND " . $filterTo . " IN (" . $inTo . ")";
      }
      /*Trae datos sin ordernar y sin limites*/
      $sql = "SELECT $select FROM $table WHERE $linkTo BETWEEN $betweenMin AND $betweenMax $filter";

      if ($orderBy != null && $orderMode != null && $startAt == null && $endAt == null) {
        /*Trae datos ordenadas y sin limites*/
        $sql = "SELECT $select FROM $table WHERE $linkTo BETWEEN $betweenMin AND $betweenMax $filter ORDER BY $orderBy $orderMode";
      } else if ($orderBy != null && $orderMode != null && $startAt != null && $endAt != null) {
        /*Trae datos ordenadas y limitados*/
        $sql = "SELECT $select FROM $table WHERE $linkTo BETWEEN $betweenMin AND $betweenMax $filter ORDER BY $orderBy $orderMode LIMIT $startAt, $endAt";
      } else if ($orderBy == null && $orderMode == null && $startAt != null && $endAt != null) {
        /*Trae datos limitados sin ordenar*/
        $sql = "SELECT $select FROM $table WHERE $linkTo BETWEEN $betweenMin AND $betweenMax $filter LIMIT $startAt, $endAt";
      }
      $stm = Connection::connect()->prepare($sql);
      try {
        $stm->execute();
      } catch (PDOException $error) {
        return null; 
      }
      $result = $stm->fetchAll(PDO::FETCH_CLASS);
      return $result;
    }
  }
  /*
  Selecciona los datos sin filtro de tablas relacionadas
  */
  static public function getRelDataRange($rel, $type, $select, $linkTo, $betweenMin, $betweenMax, $orderBy, $orderMode, $startAt, $endAt, $filterTo, $inTo)
  {
    $relArray = explode(",", $rel);
    $typeArray = explode(",", $type);
    $InnerJoinText = "";
    $filter = "";
    if (count($relArray) > 1) {
      foreach ($relArray as $key => $value) {
        if (empty(Connection::getColumnsData($value, ['*']))) {
          return null;
        }
        if ($key > 0) {
          $InnerJoinText .= "INNER JOIN " . $value . " ON " . $relArray[0] . ".id_" . $typeArray[$key] . "_" . $typeArray[0] . " = " . $value . ".id_" . $typeArray[$key] . " ";
        }
      }
      if ($filterTo != "") {
        $filter = "AND " . $filterTo . " IN (" . $inTo . ")";
      }

      /*Trae datos sin ordernar y sin limites*/
      $sql = "SELECT $select FROM $relArray[0] $InnerJoinText WHERE $linkTo BETWEEN $betweenMin AND $betweenMax $filter";

      if ($orderBy != null && $orderMode != null && $startAt == null && $endAt == null) {
        /*Trae datos ordenadas y sin limites*/
        $sql = "SELECT $select FROM $relArray[0] $InnerJoinText WHERE $linkTo BETWEEN $betweenMin AND $betweenMax $filter ORDER BY $orderBy $orderMode";
      } else if ($orderBy != null && $orderMode != null && $startAt != null && $endAt != null) {
        /*Trae datos ordenadas y limitados*/
        $sql = "SELECT $select FROM $relArray[0] $InnerJoinText WHERE $linkTo BETWEEN $betweenMin AND $betweenMax $filter ORDER BY $orderBy $orderMode LIMIT $startAt, $endAt";
      } else if ($orderBy == null && $orderMode == null && $startAt != null && $endAt != null) {
        /*Trae datos limitados sin ordenar*/
        $sql = "SELECT $select FROM $relArray[0] $InnerJoinText WHERE $linkTo BETWEEN $betweenMin AND $betweenMax $filter LIMIT $startAt, $endAt";
      }
      $stm = Connection::connect()->prepare($sql);
      try {
        $stm->execute();
      } catch (PDOException $error) {
        return null; 
      }
      $result = $stm->fetchAll(PDO::FETCH_CLASS);
      return $result;
    } else {
      return null;
    }
  }
}
