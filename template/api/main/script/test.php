<?php


require_once("class/model/Dba.php");
try{
  $dba = new Dba(); try {

    $render = new Render();
    $render->setAdvanced(["numero","=",true]);
    $render->setOrder(["numero"=>"asc"]);
    $rows = $dba->all("sede", $render);

    echo json_encode($rows);

  } finally { $dba->close(); }

} catch (Exception $ex) {
  http_response_code(500);
  error_log($ex->getTraceAsString());
  echo $ex->getMessage();
}
/*
[

["fecha",">","2017-07-01"]


,["fecha","<","2018-07-01"]]*/
