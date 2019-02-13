<?php

/**
 * @todo Implementar render en el getall
 */

require_once("config/structure.php");
require_once("config/modelClasses.php");
require_once("config/entityClasses.php");
require_once("config/valuesClasses.php");
require_once("function/snake_case_to.php");
require_once("class/db/My.php");
require_once("class/db/Pg.php");
require_once("function/toString.php");
require_once("class/model/Transaction.php");
require_once("class/model/SqlFormat.php");
require_once("function/stdclass_to_array.php");



//Facilita el acceso a la base de datos y clases del modelo
class Dba {

  /**
   * Prefijos y sufijos en el nombre de metodos:
   *   get: Utiliza id como parametro principal de busqueda
   *   all: Se refiere a un conjunto de valores
   *   one: Debe retornar un unico valor
   *   OrNull: Puede retornar valores nulos
   */
  public static $dbInstance = NULL; //conexion con una determinada db
  public static $dbCount = 0;
  public static $entities = [];
  public static $sqlFormat = NULL;

  public static function entity($entity) { //singleton Entity
    if(!array_key_exists($entity, self::$entities)){
      $entityName = snake_case_to("XxYy", $entity) . "Entity";
      $entity_ = new $entityName;
      self::$entities[$entity] = $entity_;
    }
    return self::$entities[$entity];
  }

  public static function sqlFormat() { //singleton sqlFormat
    if(is_null(self::$sqlFormat)) self::$sqlFormat = new SqlFormat();
    return self::$sqlFormat;
  }


  public static function sqlo($entity) { //instancia singleton de sqlo
    $sqloName = snake_case_to("XxYy", $entity) . "Sqlo";
    return call_user_func("{$sqloName}::getInstance");
  }

  public static function sql($entity, $prefix = NULL) { //crear instancias de sql
    /**
     * sql, a diferencia de sus pares entity y sqlo, no puede ser implementada como singleton porque utiliza prefijos de identificacion
     */
    $sqlName = snake_case_to("XxYy", $entity) . "Sql";
    $sql = new $sqlName;
    if($prefix) $sql->prefix = $prefix;
    return $sql;
  }

  public static function value($entity, array $row = NULL) { //crear instancias de values
    //TODO: Implementar metodo setRow fuera del constructor
    $name = snake_case_to("XxYy", $entity);
    $value = new $name;
    return $value;
  }

  public static function dbInstance() { //singleton db
    /**
     * Cuando se abren varios recursos de db instance se incrementa un contador, al cerrarse recursos se decrementa. Si el contador llega a 0 se cierra la instancia de la base
     */
    if (!self::$dbCount) {
      (DATA_DBMS == "pg") ?
        self::$dbInstance = new DbSqlPg(DATA_HOST, DATA_USER, DATA_PASS, DATA_DBNAME, DATA_SCHEMA) :
        self::$dbInstance = new DbSqlMy(DATA_HOST, DATA_USER, DATA_PASS, DATA_DBNAME, DATA_SCHEMA);
    }
    self::$dbCount++;
    return self::$dbInstance;
  }

  public static function dbClose() { //cerrar conexiones a la base de datos
    self::$dbCount--;
    if(!self::$dbCount) self::$dbInstance->close(); //cuando todos los recursos liberan la base de datos se cierra
    return self::$dbInstance;
  }

  public static function uniqId(){ //identificador unico
    usleep(1); //con esto se evita que los procesadores generen el mismo id
    return hexdec(uniqid());
  }

  public static function nextId($entity) { //siguiente identificador
    return self::uniqId(); //uniq id

    //postgresql
    /**
     * $sql = "select nextval('" . self::entity($entity)->sn_() . "_id_seq')";
     * $row = self::fetchRow($sql);
     * return $row[0];
     */
  }

  public static function isPersistible($entity, array $row){ //es persistible?
    $row_ = self::_unique($entity, $row); //1) Consultar valores a partir de los datos
    $sqlo = self::sqlo($entity);

    if (count($row_)){
      $row["id"] = $row_["id"];
      return $sqlo->sql->isUpdatable($row);  //2) Si 1 dio resultado, verificar si es actualizable
    }

    return $sqlo->sql->isInsertable($row); //3) Si 1 no dio resultado, verificar si es insertable
  }

  public static function display(array $params){ //generar display
    /**
     * Desde el cliente se recibe un Display, es una objeto similar a Render pero con algunas caracteristicas adicionales
     */
    $data = null;

    //data es utilizado debido a la facilidad de comunicacion entre el cliente y el servidor. Se coloca todo el json directamente en una variable data que es convertida en el servidor.
    if(isset($params["display"])) {
      $data = $params["display"];
      unset($params["display"]);
    }

    $f_ = json_decode($data);
    $display = stdclass_to_array($f_);
    if(empty($display["size"])) $display["size"] = 100;
    if(empty($display["page"])) $display["page"] = 1;
    if(!isset($display["order"])) $display["order"] = [];
    if(!isset($display["filters"])) $display["filters"] = [];

    foreach($params as $key => $value) {
      /**
       * Los parametros fuera de display, se priorizan y reasignan a Display
       * Si los atributos son desconocidos se agregan como filtros
       */
      switch($key){
        case "size": case "page": case "search": case "history"://pueden redefinirse ciertos parametros la prioridad la tiene los que estan fuera del elemento data (parametros definidos directamente)
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

  public static function render($entity, array $display = null) { //instancia de render a partir de un display
    /**
     * @todo El ordenamiento por defecto no deberia ser una caracteristica de entity sino de las clases de generacion de sql
     */
    $render = new Render();

    $render->setPagination($display["size"], $display["page"]);
    $render->setOrder($display["order"], self::entity($entity)->getOrder());

    if(!empty($display["search"])) $render->setSearch($display["search"]);
    if(!empty($display["filters"])) $render->setAdvanced($display["filters"]);
    if(!empty($display["history"])) $render->setHistory($display["history"]);
    if(!empty($display["params"])) $render->setParams($display["params"]);

    return $render;
  }

  public static function count($entity, $render = null){ //cantidad
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
    if(count($rows) == 1) return self::sqlo($entity)->json($rows[0]);
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
    if(count($rows) == 1) return self::sqlo($entity)->json($rows[0]);
    return null;
  }

  public static function ids($entity, $render = null){ //devolver ids
    $sql = self::sqlo($entity)->all($render);
    $ids = self::fetchAllColumns($sql, 0);
    array_walk($ids, "toString"); //los ids son tratados como string para evitar un error que se genera en Angular (se resta un numero en los enteros largos)
    return $ids;
  }

  public static function id($render = null) { //devolver id
    $ids = self::ids($render);
    if(count($ids) > 1 ) throw new Exception("La consulta retorno mas de un resultado");
    elseif(count($ids) == 1) return (string)$ids[0];//los ids son tratados como string para evitar un error que se genera en Angular (se resta un numero en los enteros largos)
    else throw new Exception("La consulta no arrojó resultados");
  }

  public static function idOrNull($render = null) { //devolver id o null
    $ids = self::ids($render);
    if(count($ids) > 1 ) throw new Exception("La consulta retorno mas de un resultado");
    elseif(count($ids) == 1) return (string)$ids[0]; //los ids son tratados como string para evitar un error que se genera en Angular (se resta un numero en los enteros largos)
    else return null;
  }

  public static function all($entity, $render = null){ //devolver todos los valores
    $sql = self::sqlo($entity)->all($render);
    $rows = self::fetchAll($sql);
    return self::sqlo($entity)->jsonAll($rows);
  }

  public static function get($entity, $id, $render = null) { //busqueda por id
    if(!$id) throw new Exception("No se encuentra definido el id");
    $rows = self::getAll($entity, [$id], $render);
    if (!count($rows)) throw new Exception("La búsqueda por id no arrojó ningun resultado");
    return $rows[0];
  }

  public static function getOrNull($entity, array $id, $render = null){ //busqueda por id o null
    $rows = self::getAll($entity, [$id], $render);
    return (!count($rows)) ? null : $rows[0];
  }

  public static function getAll($entity, array $ids, $render = null){ //busqueda por ids
    $sql = self::sqlo($entity)->getAll($ids, $render);
    $rows = self::fetchAll($sql);
    return self::sqlo($entity)->jsonAll($rows);
  }

  public static function one($entity, $render = null) { //un solo valor
    $rows = self::all($entity, $render);
    if(count($rows) > 1 ) throw new Exception("La consulta retorno mas de un resultado");
    elseif(count($rows) == 1) return self::sqlo($entity)->json($rows[0]);
    else throw new Exception("La consulta no arrojó resultados");
  }

  public static function oneOrNull($entity, $render = null) { //un solo valor o null
    $rows = self::all($entity, $render);
    if(count($rows) > 1 ) throw new Exception("La consulta retorno mas de un resultado");
    elseif(count($rows) == 1) return self::sqlo($entity)->json($rows[0]);
    else return null;
  }

  public static function isDeletable($entity, array $ids){ //es eliminable?
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

  /*
  public static function deleteRequiredAll($entity, array $ids, array $params = []) { //eliminacion requerida
    $sqlo = self::sqlo($entity);
    return $sqlo->deleteRequiredAll($ids, $params);
  }*/



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

    if (!empty($row_)){ //2
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

  public static function fetchAllColumns($sql, $column = 0){ //query and fetch result
    $db = self::dbInstance();
    try {
      $result = $db->query($sql);
      return $db->fetchAllColumns($result, $column);
    } finally { self::dbClose(); }
  }

}
