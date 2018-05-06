<?php
require_once("class/Filter.php");
require_once("function/stdclass_to_array.php");
require_once("class/model/Dba.php");

try{
  $params = Filter::requestAll();
  //el uso de parametros es dinamico
  //se puede definir un parametro opcional "data" que posee un string en formato json para facilitar el uso de tipos basicos


  $dba = new Dba(); try {
    $render = $dba->render(ENTITY, $params);
    $count = $dba->count(ENTITY, $render);
    echo json_encode(intval($count));

  } finally { $dba::dbClose(); }

} catch (Exception $ex) {
  http_response_code(500);
  error_log($ex->getTraceAsString());
  echo $ex->getMessage();

}
