<?php

require_once("class/Filter.php");
require_once("class/model/Dba.php");
require_once("function/stdclass_to_array.php");


try{
  $id_ = Filter::requestRequired("id");
  $id =  json_decode($id_);


  $dba = new Dba(); try {
    $isd = $dba->isDeletable(ENTITY, [$id]);

    if($isd !== true) {
      echo json_encode(["status" => false, "message" => $isd, "id" => $id]);
      return;
    }

    $idD = $dba->delete(ENTITY, $id);
    $dba->commit();
    echo json_encode(["status" => true, "message" =>null, "id" => $idD]);

  } finally { $dba->close(); }

} catch (Exception $ex) {
  error_log($ex->getTraceAsString());
  http_response_code(500);
  echo $ex->getMessage();
}
