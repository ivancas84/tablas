<?php

require_once("class/model/Dba.php");
require_once("class/Filter.php");
require_once("function/stdclass_to_array.php");


try{
  $f = Filter::requestRequired("data");
  $f_ =  json_decode($f);
  $filter = stdclass_to_array($f_);
  
  $entity = $filter["entity"];
  $params = $filter["params"];
  
  $dba = new Dba();
  try {
    $row = $dba->unique($entity, $params);

    echo json_encode($row);
  } finally {
    $dba::dbClose();
  }

} catch (Exception $ex) {
  error_log($ex->getTraceAsString());
  http_response_code(500);
  echo $ex->getMessage();
}