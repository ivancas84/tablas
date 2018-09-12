<?php

require_once("class/Filter.php");
require_once("class/model/Dba.php");
require_once("function/stdclass_to_array.php");

try{
  $f = Filter::requestRequired("data");
  $f_ =  json_decode($f);
  $data = stdclass_to_array($f_);


  $response = [];
  foreach($data as $persist){
    $entity = $persist["entity"];
    $row = (!empty($persist["row"])) ? $persist["row"]: null;
    $rows = (!empty($persist["rows"])) ? $persist["rows"]: [];
    $params = (!empty($persist["params"])) ? $persist["params"]: [];






    //***** row *****
    if(!empty($row)){
      $id = $dba->persist($entity, $row);
      array_push($response, ["entity" => $entity, "id" => $id]);
    }



    //***** rows *****
    if(count($rows)){
      $render = array();
      foreach($params as $fieldName => $fieldValue) array_push($render, ["field" => $fieldName, 'value' => $fieldValue]);
      $ids = $dba->ids($entity, $render);

      $idsReturn = array(); //claves persisitidas a retornar

      foreach($rows as $row){
        if(!empty($params)) foreach($params as $key => $value) $row[$key] = $value; //combinar datos a persisitir con los parametros

        //eliminar las pks persistidas del array de pks previamente consultado
        if(!empty($row["id"])) {
          $key = array_search($row["id"], $ids);
          if($key !== false) unset($ids[$key]);
        }

        $id = Dba::persist($entity, $row);

        array_push($idsReturn, $id);
      }

      foreach($ids as $id_) Dba::delete($entity, $id_);

      array_push($response, ["entity" => $entity, "ids" => $idsReturn]);
    }
  }

  Dba::commit();

  echo json_encode($response);

} catch (Exception $ex){
  error_log($ex->getTraceAsString());
  http_response_code(500);
  echo $ex->getMessage();
}
