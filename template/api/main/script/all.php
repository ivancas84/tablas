<?php

require_once("class/Filter.php");
require_once("class/model/Dba.php");
require_once("function/stdclass_to_array.php");

try{

  $params = Filter::requestAll();
  //el uso de parametros es dinamico
  //se puede definir un parametro opcional "data" que posee un string en formato json para facilitar el uso de tipos basicos

  $dba = new Dba(); try {
    $render = $dba->render(ENTITY, $params);
    $rows_ = $dba->all(ENTITY, $render);
    $rows = $dba->build(ENTITY, $rows_);
    echo json_encode($rows);

  } finally { $dba->close(); }

} catch (Exception $ex) {
  error_log($ex->getTraceAsString());
  http_response_code(500);
  echo $ex->getMessage();
}
