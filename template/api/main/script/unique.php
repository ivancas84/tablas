<?php


require_once("class/Filter.php");
require_once("class/model/Dba.php");
require_once("function/stdclass_to_array.php");

try{

  $params = Filter::requestAllRequired();

  //el uso de parametros es dinamico
  //se puede definir un parametro opcional "data" que posee un string en formato json para facilitar el uso de tipos basicos

    $row_ = Dba::unique(ENTITY, $params);
    if(!$row_) return null;
    $row = Dba::sqlo(ENTITY)->json($row_);
    echo json_encode($row);

} catch (Exception $ex) {
  error_log($ex->getTraceAsString());
  http_response_code(500);
  echo $ex->getMessage();
}
