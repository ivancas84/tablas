<?php

require_once("class/Filter.php");
require_once("class/model/Dba.php");
require_once("function/stdclass_to_array.php");


try{
  $id_ = Filter::requestRequired("id");
  $id =  json_decode($id_);

  $isd = Dba::isDeletable(ENTITY, [$id]);

  if($isd !== true) {
    echo json_encode(["status" => false, "message" => $isd, "id" => $id]);
    return;
  }

  Transaction::begin();
  $data = Dba::sqlo($entity)->deleteAll([$id]);
  $transaction_ids = preg_filter('/^/', $entity, $data["ids"]);
  Transaction::update(["descripcion"=> $data["sql"], "detalle" => implode(",",$transaction_ids)]);
  Transaction::commit();
  $idD = array_walk($data["ids"], "toString");
  echo json_encode(["status" => true, "message" =>null, "id" => $idD]);

} catch (Exception $ex) {
  error_log($ex->getTraceAsString());
  http_response_code(500);
  echo $ex->getMessage();
}
