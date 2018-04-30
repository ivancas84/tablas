<?php

require_once("class/Filter.php");
require_once("class/model/Dba.php");
require_once("function/stdclass_to_array.php");

try{
  $ids_ = Filter::request("ids");
  $ids =  json_decode($ids_);
  //$filter = stdclass_to_array($f_);

  $dba = new Dba(); try {
    $rows_ = $dba->getAll(ENTITY, $ids);
    $rows = $dba->build(ENTITY, $rows_);
    echo json_encode($rows);
  } finally { $dba->close(); }

} catch (Exception $ex) {
  error_log($ex->getTraceAsString());
  http_response_code(500);
  echo $ex->getMessage();
}
