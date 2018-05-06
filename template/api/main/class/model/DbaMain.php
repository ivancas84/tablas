<?php

require_once("config/structure.php");
require_once("config/modelClasses.php");
require_once("config/entityClasses.php");
require_once("function/snake_case_to.php");
require_once("class/db/My.php");

//Acceso a una determinada Db
class DbaMain {

  public static $dbInstance = NULL; //conexion con una determinada db
  public $transaction = NULL; //id de transaccion (las transacciones se guardan en variables de sesion para poder manejarlas desde el cliente)
  public $sqlos = []; //instancias de sqlo en uso (permite evitar que se instancien multiples objetos iguales)
  public $entities = []; //instancias de entities en uso (permite evitar que se instancien multiples objetos iguales)



  public static function dbInstance() {
    if (self::$dbInstance === null) self::$dbInstance = new DbSqlMy(DATA_HOST, DATA_USER, DATA_PASS, DATA_DBNAME, DATA_SCHEMA);
    return self::$dbInstance;
  }

  public static function dbClose() {
    if (self::$dbInstance != null) self::$dbInstance->close();
    return self::$dbInstance;
  }

  //siguiente id
  protected function nextId($entity) {
    $entity_ = $this->entity($entity);

    //mysql (php5.5+)
    usleep(1); //evita que genere el mismo id para procesadores rapidos
    return hexdec(uniqid());
    //postgresql

    $db = self::dbInstance();
    $sql = "select nextval('" . $entity_->getSn_() . "_id_seq')";
    $result = $db->query($sql);
    $row = $db->fetchRow($result);
    return $row[0];
  }

  //es persistible?
  public function isPersistible($entity, array $row){

    $row_ = $this->_unique($entity, $row); //1) Consultar valores a partir de los datos

    $sqlo = $this->entitySqlo($entity);

    if (count($row_)){
      $row["id"] = $row_["id"];
      return $sqlo->sql->isUpdatable($row);  //2) Si 1 dio resultado, verificar si es actualizable
    }

    return $sqlo->sql->isInsertable($row); //3) Si 1 no dio resultado, verificar si es insertable
  }



  //retornar instancia de clase render en base a un conjunto de filtros de busqueda habituales
  public function render($entity, array $params = null) {
    $data = null;
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

    foreach($params as $key => $value) array_push($display["filters"], [$key,"=",$params[$key]]);

    $entityObj = $this->entity($entity);

    $render = new Render();

    $render->setPagination($display["size"], $display["page"]);
    $render->setOrder($display["order"], $entityObj->getOrder());

    if(!empty($display["search"])) $render->setSearch($display["search"]);
    if(!empty($display["filters"])) $render->setAdvanced($display["filters"]);
    if(!empty($display["params"])) $render->addAdvanced($display["params"]);

    return $render;
  }

  //retornar instancia de entity
  public  function entity($entity){
    if(!array_key_exists($entity, $this->entities)){
      $entityName = snake_case_to("XxYy", $entity) . "Entity";
      $entity_ = new $entityName;
      $this->entities[$entity] = $entity_;
    }
    return $this->entities[$entity];
  }

  //retornar instancia de Sqlo
  public function entitySqlo($entity) {
    if(!array_key_exists($entity, $this->sqlos)){
      $sqloName = snake_case_to("XxYy", $entity) . "Sqlo";
      $sqlo = new $sqloName;
      $this->sqlos[$entity] = $sqlo;
    }
    return $this->sqlos[$entity];
  }

  //cantidad
  public function count($entity, $render = null){
    $sqlo = $this->entitySqlo($entity);
    $sql = $sqlo->count($render);
    $db = self::dbInstance();
    $result = $db->query($sql);
    $row = $db->fetchAssoc($result);
    return $row["num_rows"];
  }

  //busqueda estricta por campos unicos
  public function _unique($entity, $render = null){
    $db = self::dbInstance();
    $sqlo = $this->entitySqlo($entity);
    $sql = $sqlo->_unique($render);
    $result = $db->query($sql);
    $rows = $db->fetchAll($result);

    if(count($rows) > 1) throw new Exception("La busqueda estricta por campos unicos de " . $this->entity->getName() . " retorno mas de un resultado");
    if(count($rows) == 1) return $rows[0];
    return null;
  }

  //busqueda por campos unicos
  public function unique($entity, $render = null){
    $sqlo = $this->entitySqlo($entity);
    $sql = $sqlo->unique($render);
    $db = self::dbInstance();
    $result = $db->query($sql);
    $rows = $db->fetchAll($result);

    if(count($rows) > 1) throw new Exception("La busqueda por campos unicos de " . $this->entity->getName() . " retorno mas de un resultado");
    if(count($rows) == 1) return $rows[0];
    return null;
  }

  //all
  public function all($entity, $render = null){
    $sqlo = $this->entitySqlo($entity);
    $sql = $sqlo->all($render);
    $db = self::dbInstance();
    $result = $db->query($sql);
    return $db->fetchAll($result);
  }

  //ids
  public function ids($entity, $render = null){
    $sqlo = $this->entitySqlo($entity);
    $sql = $sqlo->all($render);

    $db = self::dbInstance();
    $result = $db->query($sql);
    return $db->fetchAllColumns($result, 0);
  }

  //id
  public function id($render = null) {
    $ids = $this->ids($render);
    if(count($ids) > 1 ) throw new Exception("La consulta retorno mas de un resultado");
    elseif(count($ids) == 1) return $ids[0];
    else throw new Exception("La consulta no arrojó resultados");
  }

  //id or null
  public function idOrNull($render = null) {
    $ids = $this->ids($render);
    if(count($ids) > 1 ) throw new Exception("La consulta retorno mas de un resultado");
    elseif(count($ids) == 1) return $ids[0];
    else return null;
  }





  //get
  public function get($entity, $id, $render = null) {
    $rows = $this->getAll($entity, [$id], $render);
    if (!count($rows)) throw new Exception("La búsqueda por id no arrojó ningun resultado");
    return $rows[0];
  }

  //get or null
  public function getOrNull($entity, array $id, $render = null){
    $rows = $this->getAll($entity, [$id], $render);
    return (!count($rows)) ? null : $rows[0];
  }

  //get all
  public function getAll($entity, array $ids, $render = null){
    $sqlo = $this->entitySqlo($entity);
    $sql = $sqlo->getAll($ids, $render);
    $db = self::dbInstance();
    $result = $db->query($sql);
    return $db->fetchAll($result);
  }

  //row
  public function one($entity, $render = null) {
    $rows = $this->all($entity, $render);
    if(count($rows) > 1 ) throw new Exception("La consulta retorno mas de un resultado");
    elseif(count($rows) == 1) return $rows[0];
    else throw new Exception("La consulta no arrojó resultados");
  }

  //row or null
  public function oneOrNull($entity, $render = null) {
    $rows = $this->all($entity, $render);
    if(count($rows) > 1 ) throw new Exception("La consulta retorno mas de un resultado");
    elseif(count($rows) == 1) return $rows[0];
    else return null;
  }


  //deleteAll
  public function deleteAll($entity, $ids){
    if(!$this->transaction) $this->begin();

    $sqlo = $this->entitySqlo($entity);
    $data = $sqlo->deleteAll($ids);

    $transaction_ids = preg_filter('/^/', $entity, $ids);

    $this->update(["descripcion"=> $data["sql"], "detalle" => implode(",",$transaction_ids)]);

    return $data["ids"];
  }


  //delete
  public function delete($entity, $id){
    $ids = $this->deleteAll($entity, [$id]);
    return $ids[0];
  }

  //es eliminable?
  public function isDeletable($entity, array $ids){
    if(!count($ids)) return "El identificador está vacío";

    $entity_ = $this->entity($entity);
    $entities = [];


    for($i = 0; $i < count($ids); $i++){
      if(empty($ids[$i])) return "El identificador está vacío";

      foreach($entity_->getFieldsRef() as $field) {
        if($this->count($field->getEntity()->getName(), [$field->getName(), "=", $ids[$i]])) array_push($entities, $field->getEntity()->getName());
      }
    }

    //print_r($entities);
    if(!count($entities)) return true;
    return "Esta asociado a " . implode(', ', array_unique($entities)) . ".";
  }


  protected function _persist($entity, array $row){
    $sqlo = $this->entitySqlo($entity);
    $row_ = $this->_unique($entity, $row); //1) consultar valores a partir de los datos (CUIDADO UTILIZAR _unique en vez de unique para no restringir datos con condiciones auxiliares)

    if (count($row_)){ //2) Si 1 dio resultado, actualizar
      $row["id"] = $row_["id"];
      return $sqlo->update($row);
    }

    else { return $sqlo->insert($row); } //3) Si 1 no dio resultado, definir pk e insertar
  }

  //persist
  public function persist($entity, array $row){
    if(!$this->transaction) $this->begin();
    $entity_ = $this->entity($entity);
    $data = $this->_persist($entity, $row);

    $this->update(["descripcion"=> $data["sql"], "detalle" => $entity_->getName() . $data["id"]]);

    return $data["id"];
  }



  //begin transaction
  public function begin($id = null){
    if($this->transaction) throw new Exception("Ya existe una transaccion iniciada");

    if(!empty($id)){
      if(empty($_SESSION["transaction"][$id])) throw new Exception("El id de transaccion es incorrecto");
      $this->transaction = $id;
      return $id;
    }

    $db = self::dbInstance();
    $this->transaction = $db->uniqId();

    $_SESSION["transaction"][$this->transaction] = [
      "sql" => null,
      "tipo" => "begin",
      "descripcion" => "",
      "detalle" => "",
      "alta" => date("Y-m-d h:i:s"),
      "actualizado" => date("Y-m-d h:i:s"),
    ];
    return $this->transaction;
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
  public function update(array $data){
    if(empty($this->transaction)) throw new UnexpectedValueException("Id de transaccion no definido");

    if(!empty($data["descripcion"])){
      if(!empty($_SESSION["transaction"][$this->transaction]["descripcion"])) $_SESSION["transaction"][$this->transaction]["descripcion"] .= " ";
      $_SESSION["transaction"][$this->transaction]["descripcion"] .= $data["descripcion"];
    }

    if(!empty($data["detalle"])){
      if(!empty($_SESSION["transaction"][$this->transaction]["detalle"])) $_SESSION["transaction"][$this->transaction]["detalle"] .= ",";
      $_SESSION["transaction"][$this->transaction]["detalle"] .= $data["detalle"];
    }

    if(!empty($data["tipo"])) $_SESSION["transaction"][$this->transaction]["tipo"] .= $data["tipo"];

    $_SESSION["transaction"][$this->transaction]["actualizado"] = date("Y-m-d h:i:s");

    return $this->transaction;
  }



  //verificar transacciones
  public function check(){
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
  public function rollback(){
    if(empty($this->transaction)) throw new UnexpectedValueException("Id de transaccion no definido");
    unset($_SESSION["transaction"][$this->transaction]);
  }

  //commit transaction
  public function commit(){
    if(empty($this->transaction)) throw new UnexpectedValueException("Id de transaccion no definido");

    $db = self::dbInstance();
    $id = $db->escapeString($this->transaction);
    $descripcion = $_SESSION["transaction"][$this->transaction]["descripcion"];
    $detalle = $_SESSION["transaction"][$this->transaction]["detalle"];
    $tipo = $_SESSION["transaction"][$this->transaction]["tipo"];
    $fecha = $_SESSION["transaction"][$this->transaction]["actualizado"];

    $queryTransaction = "
      INSERT INTO transaccion (id, actualizado, descripcion, detalle, tipo)
      VALUES (" . $id . ", '" . $fecha . "', '" . $db->escapeString($descripcion) . "', '" . $db->escapeString($detalle) . "', '" . $tipo . "');
    ";

    $db = self::dbInstance();
    $db->query($queryTransaction);

    $queryPersist = $descripcion;
    $queryPersist .= "UPDATE transaccion SET tipo = 'commit', actualizado = '" . date("Y-m-d H:i:s") . "' WHERE id = " . $id . ";";
    $db->multiQueryTransaction($queryPersist);

    unset($_SESSION["transaction"][$this->transaction]);
  }

  public function json($entity, array $rows){
    $sqlo = $this->entitySqlo($entity);
    $rows_ = [];

    foreach($rows as $row){
      $row_ = $sqlo->json($row);
      array_push($rows_, $row_);
    }

    return $rows_;
  }

  public function values($entity, array $rows){
    $sqlo = $this->entitySqlo($entity);
    $rows_ = [];


    foreach($rows as $row){
      $row_ = $sqlo->values($row);
      array_push($rows_, $row_);
    }

    return $rows_;
  }

}
