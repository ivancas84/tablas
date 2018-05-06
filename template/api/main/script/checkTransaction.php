<?php

require_once("class/model/Dba.php");

try{

  $dba = new Dba(); try {
    $details = $dba->check();
    echo json_encode(["data" => $details]);
  } finally { $dba::dbClose(); }

} catch (Exception $ex) {
  error_log($ex->getTraceAsString());
  http_response_code(500);
  echo $ex->getMessage();
}
