<?php

//script de procesamiento
//recibe informacion de un conjunto de entidades y procesa sus datos
//retorna el id principal de las entidades procesadas
//tener en cuenta que el id persistido, no siempre puede ser el id retornado (por ejemplo para el caso que se utilicen logs en la base de datos)
require_once("class/Filter.php");
require_once("class/model/Dba.php");
require_once("function/stdclass_to_array.php");

try{
  $f = Filter::requestRequired("data");
  $f_ =  json_decode($f);
  $data = stdclass_to_array($f_);
  $sql = "";
  $idsReturn = [];
  $detail = [];

  foreach($data as $d){
    $entity = $d["entity"];
    $row = (!empty($d["row"])) ? $d["row"]: null;
    $rows = (!empty($d["rows"])) ? $d["rows"]: [];
    $params = (!empty($d["params"])) ? $d["params"]: [];

    //***** row *****
    if(!empty($row)){
      $persist = Dba::persist($entity, $row);
      $sql .= $persist["sql"];
      array_push($idsReturn, $persist["id"]);
      $detail = array_merge($detail, $persist["detail"]);
    }

    //rows: Habitualmente existe una fk asociada definida en params
    if(count($rows)){
      $render = array();
      foreach($params as $fieldName => $fieldValue) array_push($render, [$fieldName, '=', $fieldValue]);
      $ids = Dba::ids($entity, $render);

      foreach($rows as $row){
        if(!empty($params)) foreach($params as $key => $value) $row[$key] = $value; //combinar datos a persisitir con los parametros

        //eliminar las pks persistidas del array de pks previamente consultado
        if(!empty($row["id"])) {
          $key = array_search($row["id"], $ids);
          if($key !== false) unset($ids[$key]);
        }

        $persist = Dba::persist($entity, $row);
        $sql .= $persist["sql"];
        array_push($idsReturn, $persist["id"]);
        $detail = array_merge($detail, $persist["detail"]);
      }

      $persist = Dba::sqlo($entity)->deleteAll($ids);
      $sql .= $persist["sql"];
      $detail = array_merge($detail, $persist["detail"]);

      array_push($response, ["entity" => $entity, "ids" => $idsReturn]);
    }

  }

  Transaction::begin();
  Transaction::update(["descripcion"=> $sql, "detalle" => implode(",",$detail)]);
  Transaction::commit();
  echo json_encode($response);

} catch (Exception $ex){
  error_log($ex->getTraceAsString());
  http_response_code(500);
  echo $ex->getMessage();
}
