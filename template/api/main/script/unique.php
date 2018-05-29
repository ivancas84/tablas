<?php


require_once("class/Filter.php");
require_once("class/model/Dba.php");
require_once("function/stdclass_to_array.php");

try{

  $params = Filter::requestAllRequired();

  //el uso de parametros es dinamico
  //se puede definir un parametro opcional "data" que posee un string en formato json para facilitar el uso de tipos basicos

  $dba = new Dba(); try {
    $rows_ = $dba->unique(ENTITY, $params);
    $sqlo = $dba->entitySqlo(ENTITY);
    $rows = $sqlo->json($rows_);
    echo json_encode($rows);

  } finally { $dba::dbClose(); }

} catch (Exception $ex) {
  error_log($ex->getTraceAsString());
  http_response_code(500);
  echo $ex->getMessage();
}
