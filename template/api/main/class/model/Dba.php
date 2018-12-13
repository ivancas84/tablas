<?php

require_once("config/structure.php");
require_once("config/modelClasses.php");
require_once("config/entityClasses.php");
require_once("function/snake_case_to.php");
require_once("class/db/My.php");
require_once("class/db/Pg.php");
require_once("function/toString.php");
require_once("class/model/Transaction.php");

//Facilita el acceso a la base de datos y clases del modelo
class Dba {

  public static $dbInstance = NULL; //conexion con una determinada db
  public static $dbCount = 0;
  public static $sqlos = [];
  public static $sqls = [];
  public static $entities = [];

  //singleton Entity
  public static function entity($entity) {
    if(!array_key_exists($entity, self::$entities)){
      $entityName = snake_case_to("XxYy", $entity) . "Entity";
      $entity_ = new $entityName;
      self::$entities[$entity] = $entity_;
    }
    return self::$entities[$entity];
  }

  //singleton sqlo
  public static function sqlo($entity) {
    if(!array_key_exists($entity, self::$sqlos)){
      $sqloName = snake_case_to("XxYy", $entity) . "Sqlo";
      $sqlo = new $sqloName;
      self::$sqlos[$entity] = $sqlo;
    }
    return self::$sqlos[$entity];
  }

  //crear instancias de sql
  public static function sql($entity, $prefix = NULL) {
    $sqlName = snake_case_to("XxYy", $entity) . "Sql";
    $sql = new $sqlName;
    if($prefix) $sql->prefix = $prefix;
    return $sql;
  }

  //singleton db
  //cuando se abren varios recursos de db instance se incrementa un contador, al cerrarse recursos se decrementa. Si el contador llega a 0 se cierra la instancia de la base
  public static function dbInstance() {
    if (!self::$dbCount) {
      (DATA_DBMS == "pg") ?
        self::$dbInstance = new DbSqlPg(DATA_HOST, DATA_USER, DATA_PASS, DATA_DBNAME, DATA_SCHEMA) :
        self::$dbInstance = new DbSqlMy(DATA_HOST, DATA_USER, DATA_PASS, DATA_DBNAME, DATA_SCHEMA);
    }
    self::$dbCount++;
    return self::$dbInstance;
  }

  //cerrar conexiones a la base de datos
  public static function dbClose() {
    self::$dbCount--;
    if(!self::$dbCount) self::$dbInstance->close(); //cuando todos los recursos liberan la base de datos se cierra
    return self::$dbInstance;
  }

  //identificador unico
  public static function uniqId(){
    usleep(1); //con esto se evita que los procesadores modernos generen el mismo id
    return hexdec(uniqid());
  }

  //siguiente identificador
  public static function nextId($entity) {
    return self::uniqId(); //mysql

    //postgresql
    $sql = "select nextval('" . self::entity($entity)->getSn_() . "_id_seq')";
    $row = self::fetchRow($sql);
    return $row[0];
  }

  //es persistible?
  public static function isPersistible($entity, array $row){
    $row_ = self::_unique($entity, $row); //1) Consultar valores a partir de los datos
    $sqlo = self::sqlo($entity);

    if (count($row_)){
      $row["id"] = $row_["id"];
      return $sqlo->sql->isUpdatable($row);  //2) Si 1 dio resultado, verificar si es actualizable
    }

    return $sqlo->sql->isInsertable($row); //3) Si 1 no dio resultado, verificar si es insertable
  }

  //para facilitar la generacion de render, se puede definir un array $display
  public static function display(array $params){
    $data = null;

    //data es utilizado debido a la facilidad de comunicacion entre el cliente y el servidor. Se coloca todo el json directamente en una variable data que es convertida en el servidor.
    if(isset($params["data"])) {
      $data = $params["data"];
      unset($params["data"]);
    }

    $f_ = json_decode($data);
    $display = stdclass_to_array($f_);
    if(empty($display["size"])) $display["size"] = 100;
    if(empty($display["page"])) $display["page"] = 1;
    if(!isset($display["order"])) $display["order"] = [];
    if(!isset($display["filters"])) $display["filters"] = [];

    foreach($params as $key => $value) {
      switch($key){
        case "size": case "page": case "search": //pueden redefinirse ciertos parametros la prioridad la tiene los que estan fuera del elemento data (parametros definidos directamente)
          $display[$key] = $value;
        break;
        case "order": //ejemplo http://localhost/programacion/api/curso/all?order={%22horario%22:%22asc%22}
          $f_ = json_decode($value);
          $display["order"] = stdclass_to_array($f_); //ordenamiento ascendente (se puede definir ordenamiento ascendente de un solo campo indicandolo en el parametro order, ejemplo order=campo)
        break;


        default: array_push($display["filters"], [$key,"=",$params[$key]]);
      }
    }

    return $display;
  }

  //generar render a partir de un display
  public static function render($entity, array $display = null) {
    $render = new Render();

    $render->setPagination($display["size"], $display["page"]);
    $render->setOrder($display["order"], self::entity($entity)->getOrder());

    if(!empty($display["search"])) $render->setSearch($display["search"]);
    if(!empty($display["filters"])) $render->setAdvanced($display["filters"]);
    if(!empty($display["params"])) $render->addAdvanced($display["params"]);

    return $render;
  }

  //cantidad
  public static function count($entity, $render = null){
    $sql = self::sqlo($entity)->count($render);
    $row = self::fetchAssoc($sql);
    return intval($row["num_rows"]);
  }

  public static function _unique($entity, array $params, $render = null){ //busqueda estricta por campos unicos
    /**
     * $params
     *   array("nombre_field" => "valor_field", ...)
     */
    $sql = self::sqlo($entity)->_unique($params, $render);
    if(!$sql) return null;
    $rows = self::fetchAll($sql);

    if(count($rows) > 1) throw new Exception("La busqueda estricta por campos unicos de {$entity} retorno mas de un resultado");
    if(count($rows) == 1) return self::sql($entity)->json($rows[0]);
    return null;
  }

  public static function unique($entity, array $params, $render = null){ //busqueda por campos unicos
    /**
     * $params
     *   array("nombre_field" => "valor_field", ...)
     */
    $sql = self::sqlo($entity)->unique($params);
    if(empty($sql)) return null;

    $rows = self::fetchAll($sql);
    if(count($rows) > 1) throw new Exception("La busqueda por campos unicos de {$entity} retorno mas de un resultado");
    if(count($rows) == 1) return self::sql($entity)->json($rows[0]);
    return null;
  }

  //all
  public static function all($entity, $render = null){
    $sql = self::sqlo($entity)->all($render);
    $rows = self::fetchAll($sql);
    return self::sql($entity)->jsonAll($rows);
  }

  //ids
  public static function ids($entity, $render = null){
    $sql = self::sqlo($entity)->all($render);
    $ids = self::fetchAllColumns($sql, 0);
    array_walk($ids, "toString"); //los ids son tratados como string para evitar un error que se genera en Angular (se resta un numero en los enteros largos)
    return $ids;
  }

  //id
  public static function id($render = null) {
    $ids = self::ids($render);
    if(count($ids) > 1 ) throw new Exception("La consulta retorno mas de un resultado");
    elseif(count($ids) == 1) return (string)$ids[0];//los ids son tratados como string para evitar un error que se genera en Angular (se resta un numero en los enteros largos)
    else throw new Exception("La consulta no arrojó resultados");
  }

  //id or null
  public static function idOrNull($render = null) {
    $ids = self::ids($render);
    if(count($ids) > 1 ) throw new Exception("La consulta retorno mas de un resultado");
    elseif(count($ids) == 1) return (string)$ids[0]; //los ids son tratados como string para evitar un error que se genera en Angular (se resta un numero en los enteros largos)
    else return null;
  }

  //get
  public static function get($entity, $id, $render = null) {
    $rows = self::getAll($entity, [$id], $render);
    if (!count($rows)) throw new Exception("La búsqueda por id no arrojó ningun resultado");
    return $rows[0];
  }

  //get or null
  public static function getOrNull($entity, array $id, $render = null){
    $rows = self::getAll($entity, [$id], $render);
    return (!count($rows)) ? null : $rows[0];
  }

  //get all
  public static function getAll($entity, array $ids, $render = null){
    $sql = self::sqlo($entity)->getAll($ids, $render);
    $rows = self::fetchAll($sql);
    return self::sql($entity)->jsonAll($rows);
  }

  //row
  public static function one($entity, $render = null) {
    $rows = self::all($entity, $render);
    if(count($rows) > 1 ) throw new Exception("La consulta retorno mas de un resultado");
    elseif(count($rows) == 1) return self::sql($entity)->json($rows[0]);
    else throw new Exception("La consulta no arrojó resultados");
  }

  //row or null
  public static function oneOrNull($entity, $render = null) {
    $rows = self::all($entity, $render);
    if(count($rows) > 1 ) throw new Exception("La consulta retorno mas de un resultado");
    elseif(count($rows) == 1) return self::sql($entity)->json($rows[0]);
    else return null;
  }




  //es eliminable?
  public static function isDeletable($entity, array $ids){
    if(!count($ids)) return "El identificador está vacío";

    $entities = [];

    for($i = 0; $i < count($ids); $i++){
      if(empty($ids[$i])) return "El identificador está vacío";

      foreach(self::entity($entity)->getFieldsRef() as $field) {
        if(self::count($field->getEntity()->getName(), [$field->getName(), "=", $ids[$i]])) array_push($entities, $field->getEntity()->getName());
      }
    }

    //print_r($entities);
    if(!count($entities)) return true;
    return "Esta asociado a " . implode(', ', array_unique($entities)) . ".";
  }

  public static function persist($entity, array $row){ //generar sql de persistencia para la entidad
    /**
     * Procedimiento:
     *   1) consultar valores a partir de los datos (CUIDADO UTILIZAR _unique en vez de unique para no restringir datos con condiciones auxiliares)
     *   2) Si 1 dio resultado, actualizar
     *   3) Si 1 no dio resultado, definir pk e insertar
     *
     * Retorno:
     *   array("id" => "id del campo persistido", "sql"=>"sql de persistencia", "detail"=>"detalle de los campos persistidos")
     *     "id": Dependiendo de la implementacion, el id del campo persistido puede no coincidir con el enviado
     *     "detail": array de elementos, cada elemento es un string concatenado de la forma entidadId, ejemplo "persona1234567890"
     */
    $sqlo = self::sqlo($entity);
    $row_ = self::_unique($entity, $row); //1

    if (count($row_)){ //2
      $row["id"] = $row_["id"];
      return $sqlo->update($row);
    }

    else { return $sqlo->insert($row); } //3
  }


  //query and fetch result
  public static function fetchRow($sql){
    $db = self::dbInstance();
    try {
      $result = $db->query($sql);
      return $db->fetchRow($result);
    } finally { self::dbClose(); }
  }

  //query and fetch result
  public static function fetchAssoc($sql){
    $db = self::dbInstance();
    try {
      $result = $db->query($sql);
      return $db->fetchAssoc($result);
    } finally { self::dbClose(); }
  }

  //query and fetch result
  public static function fetchAll($sql){
    $db = self::dbInstance();
    try {
      $result = $db->query($sql);
      return $db->fetchAll($result);
    } finally { self::dbClose(); }
  }

  //query and fetch result
  public static function fetchAllTimeAr($sql){
    $db = self::dbInstance();
    try {
      $db->query("SET lc_time_names = 'es_AR';");
      $result = $db->query($sql);
      return $db->fetchAll($result);
    } finally {
      self::dbClose();
    }
  }

  //query and fetch result
  public static function fetchAllColumns($sql, $column){
    $db = self::dbInstance();
    try {
      $result = $db->query($sql);
      return $db->fetchAllColumns($result, 0);
    } finally { self::dbClose(); }
  }

}
