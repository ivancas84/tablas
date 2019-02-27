<?php

require_once("class/Filter.php");
require_once("class/model/Dba.php");

try{

  $params = Filter::postAll();
  //el uso de parametros es dinamico
  //se puede definir un parametro opcional "display" que posee un string en formato json para facilitar el uso de tipos basicos

  $display = Dba::display($params);

  $render = Dba::render(ENTITY, $display);
  $rows = Dba::all(ENTITY, $render);
  echo json_encode($rows);

} catch (Exception $ex) {
  error_log($ex->getTraceAsString());
  http_response_code(500);
  echo $ex->getMessage();
}
