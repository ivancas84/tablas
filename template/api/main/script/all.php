<?php

require_once("class/Filter.php");
require_once("class/model/Dba.php");
require_once("function/stdclass_to_array.php");

try{

  $params = Filter::requestAll();
  //el uso de parametros es dinamico
  //se puede definir un parametro opcional "data" que posee un string en formato json para facilitar el uso de tipos basicos

  $render = Dba::render(ENTITY, $params);
  $rows_ = Dba::all(ENTITY, $render);
  $rows = Dba::sqlo(ENTITY)->jsonAll($rows_);
  echo json_encode($rows);

} catch (Exception $ex) {
  error_log($ex->getTraceAsString());
  http_response_code(500);
  echo $ex->getMessage();
}
