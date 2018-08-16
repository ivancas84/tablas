<?php

require_once("function/snake_case_to.php");
require_once("class/model/Sql.php");
require_once("class/db/Interface.php");
require_once("function/settypebool.php");
require_once("class/model/Render.php");


//SQL Object
//Permite crear instancias que sirven para definir SQL
abstract class EntitySqlo {

  protected $entity; //Entity. Configuracion de la entidad
  protected $db;     //Para definir el sql es necesaria la existencia de una clase de acceso abierta, ya que ciertos metodos, como por ejemplo "escapar caracteres" lo requieren. Puede requerirse adicionalmente determinar el motor de base de datos para definir la sintaxis adecuada
  protected $sql;    //EntitySql. Atributo auxiliar para facilitar la definicion de consultas sql


  public function _json(array $row, $prefix = "") { throw new BadMethodCallException("No implementado"); }
  public function json(array $row) { return $this->_json($row); }

  public function _values(array $row, $prefix = "") { throw new BadMethodCallException("No implementado"); }
  public function values(array $row) { return $this->_values($row); }

  public function nextPk(){ return $this->db->uniqId(); }
  //Definir clase de presentacion
  //@param String | Object | Array | Render En función del tipo de parámetro define el render
  //@return Render Clase de presentacion
  protected function render($render = null){
    if(gettype($render) == "object") return $render;

    $r = new Render();
    if(gettype($render) == "string") $r->setSearch($render);
    elseif (gettype($render) == "array") $r->setAdvanced($render);
    return $r;
  }

  //Definir sql de insercion
  //@return String sql de insercion
  protected function _insertSql(array $row) { throw new Exception("Metodo abstracto sin implementar: EntitySqlo.insertSql"); }

  //Definir sql de actualizacion
  //@return String sql de actualizacion
  protected function _updateSql(array $row) { throw new Exception("Metodo abstracto no implementado: EntitySqlo.updateSql"); }

  //Inicializar sql de insercion
  protected function initializeInsertSql(array $row) { throw new Exception("Metodo abstracto no implementado: EntitySqlo.updateSql"); }

  //Inicializar sql de actualizacion
  protected function initializeUpdateSql(array $row) { throw new Exception("Metodo abstracto no implementado: EntitySqlo.updateSql"); }

  //Formatear SQL
  protected function formatSql(array $row) { throw new Exception("Metodo abstracto no implementado: EntitySqlo.updateSql"); }


  //Formatear valores y definir sql de insercion
  //La insercion tiene en cuenta todos los campos correspondientes a la tabla, si no estan definidos, les asigna "null" o valor por defecto
  //Puede incluirse un id en el array de parametro, si no esta definido se definira uno automaticamente
  //@return array("id" => "identificador insertado", "sql" => "sql de insercion")
  public function insert(array $row) {
    $r = $this->initializeInsertSql($row);
    $r_ = $this->formatSql($r);
    $sql = $this->_insertSql($r_);

    return array("id" => $r["id"], "sql" => $sql);
  }



  //Formatear valores y definir sql de actualizacion
  //La actualizacion solo tiene en cuenta los campos definidos, los que no estan definidos, no seran considerados manteniendo su valor previo.
  //No se puede actualizar el id
  //@return array("id" => "identificador insertado", "sql" => "sql de insercion")
  public function update(array $row) {
    $r = $this->initializeUpdateSql($row);
    $r_ = $this->formatSql($r);
    $sql = $this->_updateSql($r_);

    return array("id" => $r["id"], "sql" => $sql);
  }



  //@override
  public function deleteAll(array $ids){
    $sql = "";
    for($i = 0; $i < count($ids); $i++){
      $r = $this->formatSql(["id"=>$ids[$i] ]);

      $sql .= "
DELETE FROM " . $this->entity->getSn_() . "
WHERE " . $this->entity->getPk()->getName() . " = " . $r["id"] . ";
";
    }

      return ["ids"=>$ids, "sql"=>$sql];
  }

  //@override
  public function count($render = NULL) {
    $r = $this->render($render);

    $sql = "
SELECT count(DISTINCT " . $this->sql->fieldId() . ") AS \"num_rows\"
";
    $sql .= $this->sql->from();
    $sql .= $this->sql->join();
    $sql .= $this->sql->joinAux();
    $sql .= $this->sql->conditionAll($r->getAdvanced(), $r->getSearch());
    $sql .= ";
";
    return $sql;
  }


  public function all($render = NULL) {
    $r = $this->render($render);

    $sql = "SELECT DISTINCT
{$this->sql->fieldsAll()}
{$this->sql->from()}
{$this->sql->join()}
{$this->sql->joinAux()}
{$this->sql->conditionAll($r->getAdvanced(), $r->getSearch())}
{$this->sql->orderBy($r->getOrder())}
{$this->sql->limit($r->getPage(), $r->getSize())}
";

    return $sql;
  }

  //@override
  public function getAll(array $ids, $render = NULL) {
    $r = $this->render($render);

    //Para dar soporte a distintos tipos de id, se define la condicion de ids a traves del metodo conditionAdvanced en vez de utilizar IN como se hacia habitualmente
    $advanced = [];
    for($i = 0; $i < count($ids); $i++){ array_push($advanced, ["id", "=", $ids[$i], "OR"]); }
    if(!count($advanced)) return [];

    $r->addAdvanced($advanced);

    return $this->all($r);
  }



  //Implementacion auxiliar de unique
  //unique puede restringir el acceso a datos dependiendo del rol y la condicion auxiliar
  //Ciertos metodos pueden llegar a requerir la busqueda a traves de campos unicos sin recurrir a la condicion auxiliar
  public function _unique(array $row){
    $conditionUniqueFields = $this->sql->conditionUniqueFields($row);
    if(empty($conditionUniqueFields)) return null;

    $sql = "SELECT DISTINCT ";
    $sql .= $this->sql->fieldsAll();
    $sql .= $this->sql->from();
    $sql .= $this->sql->join();
    $sql .= $this->sql->joinAux();
    $sql .= "WHERE ";
    $sql .= $conditionUniqueFields;
    $sql .= ";
";

    return $sql;
  }


  //@override
  //TODO: Falta incluir condicion auxiliar
  public function unique(array $row, $render = NULL){
    $r = $this->render($render);

    $conditionUniqueFields = $this->sql->conditionUniqueFields($row);
    if(empty($conditionUniqueFields)) return null;

    $sql = "SELECT DISTINCT ";
    $sql .= $this->sql->fieldsAll();
    $sql .= $this->sql->from();
    $sql .= $this->sql->join();
    $sql .= $this->sql->joinAux();
    $sql .= "WHERE ";
    $sql .= $conditionUniqueFields;
    $sql .=  $this->sql->conditionAll($r->getAdvanced(), $r->getSearch(), "AND");

    $sql .= ";
";

    return $sql;
  }



  /* DEPRECATED
  //TODO refactorizar metodo
  public function uploadCsv($handle) {
    //***** definir headers *****
    $headersAux = fgetcsv($handle, 0, ';'); if(is_null($headersAux)) throw new Exception("Archivo CSV no válido");
    $headers = array_map('trim', $headersAux);

    //***** recorrer archivo csv y definir sql *****
    $sql = ""; //se define todo el sql y se ejecuta conjuntamente
    $rowCount = 0; //se utiliza un contador para indicar el numero de fila correspondiente al error, si es que existe alguno
    $errores = ""; //se almacenan en un string los errores

    while (($dataCsv = fgetcsv($handle, 0, ';')) !== FALSE) {
      $rowCount++;

      $dataAux = array_map('trim', $dataCsv);
      $data = array_combine($headers, $dataAux);
      try{
        $persist = $this->persistSql($data);
        $sql .= $persist["sql"];
      }
      catch (Exception $ex){
        $errores .= $ex->getMessage() . " (fila " . $rowCount . ")
";
      }

    }

    //persistir datos
    $this->db->multiQueryTransaction($sql);
  }*/


}
