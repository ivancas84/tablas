<?php

/**
 * Script de procesamiento
 * El objetivo de este script es procesar un conjunto de entidades evitando multiples accesos a la base de datos
 * Recibe un array de objetos {entity:"entidad", row:objeto con valores} o {entity:"entidad", rows:Array de objetos con valores}
 * Retorna el id principal de las entidades procesadas
 * Tener en cuenta que el id persistido, no siempre puede ser el id retornado (por ejemplo para el caso que se utilicen logs en la base de datos)
 * Es importante el orden de procesamiento, una entidad a procesar puede requerir una entidad previamente procesada
*/
require_once("class/Filter.php");
require_once("class/model/Dba.php");
require_once("function/stdclass_to_array.php");



function rows($entity, array $rows = [], array $options = []){ //procesar un conjunto de rows
  /**
   * $rows:
   *   Valores a persisitir
   *
   * $options:
   *   Posee datos de identificacion para determinar los valores actuales y modificarlos o eliminarlos segun corresponda
   *   Array asociativo, ejemplo {"field":"valor"}, habitualmente "field" corresponde al nombre de una clave foranea
   *
   * Procedimiento:
   *   1) obtener $ids actuales en base a $options
   *   2) recorrer los datos a persistir $rows:
   *      a) Combinarlos con los parametros $options
   *      b) Comparar $row["id"] con $id, si es igual, eliminar $id del array
   */
  $ret = [ "ids" => [], "sql" => "", "detail" => [] ];
  if(empty($rows)) return $ret;

  $idsActuales = [];
  if(!empty($options)) {
    $render = array();
    foreach($options as $fieldName => $fieldValue) array_push($render, [$fieldName, '=', $fieldValue]);
    $idsActuales = Dba::ids($entity, $render);
  }

  foreach($rows as $row){
    if(!empty($options)) foreach($options as $key => $value) $row[$key] = $value; //combinar datos a persisitir con los parametros

    if(!empty($row["id"])) { //eliminar id persistido del array de $ids previamente consultado
      $key = array_search($row["id"], $idsActuales);
      if($key !== false) unset($idsActuales[$key]);
    }

    $persist = Dba::delete($entity, $idsActuales, $options);
    /**
     * La eliminacion puede ser fisica, logica o simplemente puede nulificar ciertos campos
     * El parametro $options, puede ser utilizado para indicar la nulificacion
     */
    $ret["sql"] .= $persist["sql"];
    $ret["detail"] = array_merge($ret["detail"], $persist["detail"]);

    $persist = Dba::persist($entity, $row);
    $ret["sql"] .= $persist["sql"];
    array_push($ret["ids"], $persist["id"]);
    $ret["detail"] = array_merge($ret["detail"], $persist["detail"]);
  }
}

function row($entity, $row) { //persistir row
  /**
   * $row:
   *   Valores a persisitir
   */
  $ret = [ "id" => null, "sql" => "", "detail" => [] ];
  if(empty($row)) return $ret;
  return Dba::persist($entity, $row);
}


try {
  $f = Filter::requestRequired("data");

  $f_ =  json_decode($f);
  $data = stdclass_to_array($f_);

  $sql = "";
  $response = [];
  $detail = [];

  foreach($data as $d) {
    $entity = $d["entity"];
    $row = (!empty($d["row"])) ? $d["row"]: null;
    $rows = (!empty($d["rows"])) ? $d["rows"]: [];
    $options = (!empty($d["options"])) ? $d["options"]: [];


    if(!empty($row)) {
      $persist = row($entity, $row);
      $sql .= $persist["sql"];
      $detail = array_merge($detail, $persist["detail"]);
      if(!empty($persist["id"])) array_push($response, ["entity" => $entity, "id" => $persist["id"]]);
    }


    if(!empty($rows)){
      $persist = rows($entity, $rows, $options);
      $sql .= $persist["sql"];
      $detail = array_merge($detail, $persist["detail"]);
      if(!empty($persist["ids"])) array_push($response, ["entity" => $entity, "ids" => $persist["ids"]]);
    }
  }

  Transaction::begin();
  Transaction::update(["descripcion"=> $sql, "detalle" => implode(",",$detail)]);
  Transaction::commit();

  echo json_encode($response);

} catch (Exception $ex) {
  error_log($ex->getTraceAsString());
  http_response_code(500);
  echo $ex->getMessage();
}
