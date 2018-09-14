<?php

require_once("config/structure.php");
require_once("config/modelClasses.php");
require_once("config/entityClasses.php");
require_once("function/snake_case_to.php");
require_once("class/db/My.php");
require_once("class/db/Pg.php");

//Facilita el acceso a la base de datos y clases del modelo
class DbaMain {

  public static $dbInstance = NULL; //conexion con una determinada db
  public static $dbCount = 0;
  public static $sqlos = [];
  public static $sqls = [];
  public static $entities = [];
  public static $transaction = null;




  public static function sqlo($entity) {
    if(!array_key_exists($entity, self::$sqlos)){
      $sqloName = snake_case_to("XxYy", $entity) . "Sqlo";
      $sqlo = new $sqloName;
      self::$sqlos[$entity] = $sqlo;
    }
    return self::$sqlos[$entity];
  }

  public static function sql($entity) {
    if(!array_key_exists($entity, self::$sqls)){
      $sqlName = snake_case_to("XxYy", $entity) . "Sql";
      $sql = new $sqlName;
      self::$sqls[$entity] = $sql;
    }
    return self::$sqls[$entity];
  }


  //se abre un único recurso de la base de datos y se mantiene abierto hasta que finaliza la ejecucion
  public static function dbInstance() {
    if (self::$dbInstance === null) {
      (DATA_DBMS == "pg") ?
        self::$dbInstance = new DbSqlPg(DATA_HOST, DATA_USER, DATA_PASS, DATA_DBNAME, DATA_SCHEMA) :
        self::$dbInstance = new DbSqlMy(DATA_HOST, DATA_USER, DATA_PASS, DATA_DBNAME, DATA_SCHEMA);
    }
    self::$dbCount++;
    return self::$dbInstance;
  }

  //$dbInstance Instancia de la clase db que será finalizada
  public static function dbClose() {
    self::$dbCount--;
    if(!self::$dbCount === 0) self::$dbInstance->close(); //cuando todos los recursos liberan la base de datos se cierra
    return true;
  }

  //siguiente id
  public static function nextId($entity) {
    //mysql (php5.5+)
    usleep(1); //evita que genere el mismo id para procesadores rapidos
    return hexdec(uniqid());

    //postgresql
    $sql = "select nextval('" . self::entity($entity)->getSn_() . "_id_seq')";
    $row = self::fetchRow($sql);
    return $row[0];
  }

  public static function uniqId(){
    $db = self::dbInstance();
    try {
      return $db->uniqId();
    } finally {
      self::dbClose();
    }
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



  //retornar instancia de clase render en base a un conjunto de filtros de busqueda habituales
  public static function render($entity, array $params = null) {
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

    $render = new Render();

    $render->setPagination($display["size"], $display["page"]);
    $render->setOrder($display["order"], self::entity($entity)->getOrder());

    if(!empty($display["search"])) $render->setSearch($display["search"]);
    if(!empty($display["filters"])) $render->setAdvanced($display["filters"]);
    if(!empty($display["params"])) $render->addAdvanced($display["params"]);

    return $render;
  }

  //retornar instancia de entity
  public static function entity($entity){
    if(!array_key_exists($entity, self::$entities)){
      $entityName = snake_case_to("XxYy", $entity) . "Entity";
      $entity_ = new $entityName;
      self::$entities[$entity] = $entity_;
    }
    return self::$entities[$entity];
  }


  //cantidad
  public static function count($entity, $render = null){
    $sql = self::sqlo($entity)->count($render);
    $row = self::fetchAssoc($sql);
    return $row["num_rows"];
  }

  //busqueda estricta por campos unicos
  public static function _unique($entity, $render = null){
    $sql = self::sqlo($entity)->_unique($render);
    $rows = self::fetchAll($sql);

    if(count($rows) > 1) throw new Exception("La busqueda estricta por campos unicos de " . $this->entity->getName() . " retorno mas de un resultado");
    if(count($rows) == 1) return $rows[0];
    return null;
  }

  //busqueda por campos unicos
  public static function unique($entity, $render = null){
    $sql = self::sqlo($entity)->unique($render);
    if(empty($sql)) return null;

    $rows = self::fetchAll($sql);
    if(count($rows) > 1) throw new Exception("La busqueda por campos unicos de " . $this->entity->getName() . " retorno mas de un resultado");
    if(count($rows) == 1) return $rows[0];
    return null;
  }

  //all
  public static function all($entity, $render = null){
    $sql = self::sqlo($entity)->all($render);
    return self::fetchAll($sql);
  }

  //ids
  public static function ids($entity, $render = null){
    $sql = self::sqlo($entity)->all($render);
    return self::fetchAllColumns($sql, 0);
  }



  //id
  public static function id($render = null) {
    $ids = self::ids($render);
    if(count($ids) > 1 ) throw new Exception("La consulta retorno mas de un resultado");
    elseif(count($ids) == 1) return $ids[0];
    else throw new Exception("La consulta no arrojó resultados");
  }

  //id or null
  public static function idOrNull($render = null) {
    $ids = self::ids($render);
    if(count($ids) > 1 ) throw new Exception("La consulta retorno mas de un resultado");
    elseif(count($ids) == 1) return $ids[0];
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
    return self::fetchAll($sql);
  }

  //row
  public static function one($entity, $render = null) {
    $rows = self::all($entity, $render);
    if(count($rows) > 1 ) throw new Exception("La consulta retorno mas de un resultado");
    elseif(count($rows) == 1) return $rows[0];
    else throw new Exception("La consulta no arrojó resultados");
  }

  //row or null
  public static function oneOrNull($entity, $render = null) {
    $rows = self::all($entity, $render);
    if(count($rows) > 1 ) throw new Exception("La consulta retorno mas de un resultado");
    elseif(count($rows) == 1) return $rows[0];
    else return null;
  }


  //deleteAll
  public static function deleteAll($entity, $ids){
    if(!self::$transaction) self::begin();
    $data = self::sqlo($entity)->deleteAll($ids);
    $transaction_ids = preg_filter('/^/', $entity, $ids);
    self::update(["descripcion"=> $data["sql"], "detalle" => implode(",",$transaction_ids)]);
    return $data["ids"];
  }


  //delete
  public static function delete($entity, $id){
    $ids = self::deleteAll($entity, [$id]);
    return $ids[0];
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


  public static function _persist($entity, array $row){
    $sqlo = self::sqlo($entity);
    $row_ = self::_unique($entity, $row); //1) consultar valores a partir de los datos (CUIDADO UTILIZAR _unique en vez de unique para no restringir datos con condiciones auxiliares)

    if (count($row_)){ //2) Si 1 dio resultado, actualizar
      $row["id"] = $row_["id"];
      return $sqlo->update($row);
    }

    else { return $sqlo->insert($row); } //3) Si 1 no dio resultado, definir pk e insertar
  }

  //persist
  public static function persist($entity, array $row){
    if(!self::$transaction) self::begin();
    $data = self::_persist($entity, $row);
    self::update(["descripcion"=> $data["sql"], "detalle" => self::entity($entity)->getName() . $data["id"]]);

    return $data["id"];
  }

  //begin transaction
  public static function begin($id = null) {
    if(self::$transaction) throw new Exception("Ya existe una transaccion iniciada");

    if(!empty($id)){
      if(empty($_SESSION["transaction"][$id])) throw new Exception("El id de transaccion es incorrecto");
      self::$transaction = $id;
      return $id;
    }

    self::$transaction = self::uniqId();

    $_SESSION["transaction"][self::$transaction] = [
      "sql" => null,
      "tipo" => "begin",
      "descripcion" => "",
      "detalle" => "",
      "alta" => date("Y-m-d h:i:s"),
      "actualizado" => date("Y-m-d h:i:s"),
    ];
    return self::$transaction;
  }

  /*
  public function update(array $data){
    $d = $this->db->escapeString($data["descripcion"]);
    $de = $this->db->escapeString($data["detalle"]);

    $query = "
UPDATE transaccion
SET descripcion = CONCAT_WS(' ', descripcion, '" . $d . "'),
detalle = CONCAT_WS(',', detalle, '" . $de . "'),
tipo = 'transaction'
WHERE id = " . $this->id . ";";

    $db->query($query);
    return $this->id;

  }*/

  //actualizar transaccion
  public static function update(array $data){
    if(empty(self::$transaction)) throw new UnexpectedValueException("Id de transaccion no definido");

    if(!empty($data["descripcion"])){
      if(!empty($_SESSION["transaction"][self::$transaction]["descripcion"])) $_SESSION["transaction"][self::$transaction]["descripcion"] .= " ";
      $_SESSION["transaction"][self::$transaction]["descripcion"] .= $data["descripcion"];
    }

    if(!empty($data["detalle"])){
      if(!empty($_SESSION["transaction"][self::$transaction]["detalle"])) $_SESSION["transaction"][self::$transaction]["detalle"] .= ",";
      $_SESSION["transaction"][self::$transaction]["detalle"] .= $data["detalle"];
    }

    if(!empty($data["tipo"])) $_SESSION["transaction"][self::$transaction]["tipo"] .= $data["tipo"];

    $_SESSION["transaction"][self::$transaction]["actualizado"] = date("Y-m-d h:i:s");

    return self::$transaction;
  }



  //verificar transacciones
  public static function check(){
    $timestampCheck = (!empty($_SESSION["check_transaction"])) ? $_SESSION["check_transaction"] : null;
    $_SESSION["check_transaction"] = date("Y-m-d H:i:s");

    if(!isset($timestampCheck)) return "CLEAR";

    $query = "
SELECT id, detalle, actualizado
FROM transaccion
WHERE tipo = 'commit'
AND actualizado > '" . $timestampCheck . "'
ORDER BY actualizado ASC
LIMIT 20;
";
    $db = self::dbInstance();
    $result = $db->query($query);
    $numRows = intval($db->numRows($result));

    if($numRows > 0){
      if($numRows == 20) return "CLEAR";

      $rows = $db->fetchAll($result);

      $de = "";
      foreach($rows as $row){
        $d = $row["detalle"];
        if(!empty($de)) $de .= ",";
        $de .= $d;
      }
      return array_unique(explode(",", $de));
    }
  }

  /*
  public function check(){
    require_once("class/Transaction.php");

    $transaction = new Transaction();
    $details = $transaction->check();
    echo json_encode($details);
    require_once("class/session/Php.php");
    require_once("class/db/Db.php");

    $session = new SessionPhp();
    $timestampCheck = $session->get("check_transaction");
    $timestampLastCheck = $session->get("last_check_transaction");

    if(!isset($timestampCheck) || !isset($timestampLastCheck)) {
      $session->set("check_transaction", date("Y-m-d H:i:s"));
      $session->set("last_check_transaction", date("Y-m-d H:i:s"));
      exit("CLEAR");
    }

    $db = new Db();
    try{
      $query = "
SELECT id, detalle, fecha
FROM transaccion
WHERE tipo = 'commit'
AND fecha > '" . $timestampCheck . "'
AND fecha <= '" . $timestampLastCheck . "'
ORDER BY fecha ASC
LIMIT 10;
";
      $result = $db->query($query);
      $numRows = intval($db->numRows($result));

      if($numRows > 0){
        if($numRows== 10){
          $session->set("check_transaction", date("Y-m-d H:i:s"));
          $session->set("last_check_transaction", date("Y-m-d H:i:s"));

        } else {
          $rows = $db->fetchAll($result);

          $lastRow = $rows[$numRows-1];
          $session->set("check_transaction", $lastRow["fecha"]);
          $session->set("last_check_transaction", $lastRow["fecha"]);
          $lastId = $lastRow["id"];
          $lastDate = DateTime::createFromFormat("Y-m-d H:i:s", $lastRow["fecha"]);

          $details = array();
          foreach($rows as $row){
            $d = $row["detalle"];
            array_push($details, $d);
          }

          echo implode(",", $details);
        }
      } else { $session->set("last_check_transaction", date("Y-m-d H:i:s")); }
    } finally { $db->close(); }
  }*/

  //rollback transaction
  public static function rollback(){
    if(empty(self::$transaction)) throw new UnexpectedValueException("Id de transaccion no definido");
    unset($_SESSION["transaction"][self::$transaction]);
    self::$transaction = null;
  }

  //commit transaction
  public static function commit(){
    if(empty(self::$transaction)) throw new UnexpectedValueException("Id de transaccion no definido");

    $db = self::dbInstance();
    try {
      $id = $db->escapeString(self::$transaction);
      $descripcion = $_SESSION["transaction"][self::$transaction]["descripcion"];
      $detalle = $_SESSION["transaction"][self::$transaction]["detalle"];
      $tipo = $_SESSION["transaction"][self::$transaction]["tipo"];
      $fecha = $_SESSION["transaction"][self::$transaction]["actualizado"];

      $queryTransaction = "
        INSERT INTO transaccion (id, actualizado, descripcion, detalle, tipo)
        VALUES (" . $id . ", '" . $fecha . "', '" . $db->escapeString($descripcion) . "', '" . $db->escapeString($detalle) . "', '" . $tipo . "');
      ";

      $db->query($queryTransaction);

      $queryPersist = $descripcion;
      $queryPersist .= "UPDATE transaccion SET tipo = 'commit', actualizado = '" . date("Y-m-d H:i:s") . "' WHERE id = " . $id . ";";
      $db->multiQueryTransaction($queryPersist);

      unset($_SESSION["transaction"][self::$transaction]);
      self::$transaction = null;
    } finally {
      self::dbClose();
    }
  }


  public static function fetchRow($sql){
    $db = self::dbInstance();
    try {
      $result = $db->query($sql);
      return $db->fetchRow($result);
    } finally { self::dbClose(); }
  }

  public static function fetchAssoc($sql){
    $db = self::dbInstance();
    try {
      $result = $db->query($sql);
      return $db->fetchAssoc($result);
    } finally { self::dbClose(); }
  }

  public static function fetchAll($sql){
    $db = self::dbInstance();
    try {
      $result = $db->query($sql);
      return $db->fetchAll($result);
    } finally { self::dbClose(); }
  }

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

  public static function fetchAllColumns($sql, $column){
    $db = self::dbInstance();
    try {
      $result = $db->query($sql);
      return $db->fetchAllColumns($result, 0);
    } finally { self::dbClose(); }
  }

}
