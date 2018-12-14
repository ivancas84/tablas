<?php

require_once("function/snake_case_to.php");
require_once("class/model/Sql.php");
require_once("class/db/Interface.php");
require_once("function/settypebool.php");
require_once("class/model/Render.php");


//SQL Object
//Permite crear instancias que sirven para definir SQL
abstract class EntitySqlo {

  public $entity; //Entity. Configuracion de la entidad
  public $db;     //Para definir el sql es necesaria la existencia de una clase de acceso abierta, ya que ciertos metodos, como por ejemplo "escapar caracteres" lo requieren. Puede requerirse adicionalmente determinar el motor de base de datos para definir la sintaxis adecuada
  public $sql;    //EntitySql. Atributo auxiliar para facilitar la definicion de consultas sql

  public function nextPk(){ return $this->db->uniqId(); } //siguiente identificador unico
  protected function initializeInsertSql(array $row) { throw new Exception("Metodo abstracto no implementado: EntitySqlo.updateSql"); } //inicializar valores para insercion
  protected function _insertSql(array $row) { throw new Exception("Metodo abstracto sin implementar: EntitySqlo.insertSql"); } //sql de insercion
  protected function initializeUpdateSql(array $row) { throw new Exception("Metodo abstracto no implementado: EntitySqlo.updateSql"); } //inicializar valores para actualizacion
  protected function _update(array $row) { throw new Exception("Metodo abstracto no implementado: EntitySqlo.updateSql"); } //sql de actualizacion
  protected function formatSql(array $row) { throw new Exception("Metodo abstracto no implementado: EntitySqlo.updateSql"); } //formato de sql

  protected function render($render = null){ //Definir clase de presentacion
    /**
     * @param String | Object | Array | Render En función del tipo de parámetro define el render
     * @return Render Clase de presentacion
     */
    if(gettype($render) == "object") return $render;

    $r = new Render();
    if(gettype($render) == "string") $r->setSearch($render);
    elseif (gettype($render) == "array") $r->setAdvanced($render);
    return $r;
  }

  public function insert(array $row) { //Formatear valores y definir sql de insercion
    /**
     * La insercion tiene en cuenta todos los campos correspondientes a la tabla, si no estan definidos, les asigna "null" o valor por defecto
     * Puede incluirse un id en el array de parametro, si no esta definido se definira uno automaticamente
     * @return array("id" => "identificador principal actualizado", "sql" => "sql de actualizacion", "detail" => "detalle de campos modificados")
     */
    $r = $this->initializeInsertSql($row);
    $r_ = $this->formatSql($r);
    $sql = $this->_insertSql($r_);

    return array("id" => $r["id"], "sql" => $sql, "detail"=>[$this->entity->getName().$r["id"]]);
  }

  public function update(array $row, $id = null) { //sql de actualizacion
    /**
     * Formatear valores y definir sql de actualizacion
     * La actualizacion solo tiene en cuenta los campos definidos, los que no estan definidos, no seran considerados manteniendo su valor previo.
     * Se define el id a actualizar en el row
     * retorna array("id" => "identificador principal actualizado", "sql" => "sql de actualizacion", "detail" => "detalle de campos modificados")
     * TODO: recibir un id opcional como parametro prioritario, y permitir actualizacion de ids
     */
    if(empty($r["id"] && empty($id)) throw new Exception("No existe identificador definido");
    $r = $this->initializeUpdateSql($row);
    $r_ = $this->formatSql($r);
    $id = if(empty($id)) $id = $r_["id"];
    $sql = "
{$this->_updateSql($r_)}
WHERE id = {$id};
";

    return array("id" => $r_["id"], "sql" => $sql, "detail"=>[$this->entity->getName().$r["id"]]);
  }

  public function updateAll($row, $ids) { //sql de actualizacion para un conjunto de ids
    /**
     * Formatear valores y definir sql de actualizacion para un conjunto de ids
     * La actualizacion solo tiene en cuenta los campos definidos, los que no estan definidos, no seran considerados manteniendo su valor previo.
     * TODO: La version actual no permite actualizar el id, pero no estoy seguro por que no, cambiarlo para que permita
     * @return array("id" => "identificador principal actualizado", "sql" => "sql de actualizacion", "detail" => "detalle de campos modificados")
     */
    if(empty($ids) throw new Exception("No existen identificadores definidos");
    $r = $this->initializeUpdateSql($row);
    $r_ = $this->formatSql($r);
    $sql = "
{$this->_updateSql($r_)}
WHERE ids IN ({implode(',', $ids)});
";
    $detail = $ids;
    array_walk($detail, function(&$item) { $item = $this->entity.$item; });
    return ["ids"=>$ids, "sql"=>$sql, "detail"=>$detail];
  }

  public function delete($id){ //eliminar
    $delete = $this->deleteAll([$id]);
    return ["id"=>$delete["ids"][0], "sql"=>$delete["sql"], "detail"=>$delete["detail"]];
  }

  public function deleteAll(array $ids){ //eliminar
    $sql = "";
    for($i = 0; $i < count($ids); $i++){
      $r = $this->formatSql(["id"=>$ids[$i] ]);

      $sql .= "
DELETE FROM " . $this->entity->getSn_() . "
WHERE " . $this->entity->getPk()->getName() . " = " . $r["id"] . ";
";
    }

      $detail = $ids;
      array_walk($detail, function(&$item) { $item = $this->entity.$item; });
      return ["ids"=>$ids, "sql"=>$sql, "detail"=>$detail];
  }

  public function count($render = NULL) { //sql cantidad de valores
    $r = $this->render($render);

    return "
SELECT count(DISTINCT " . $this->sql->fieldId() . ") AS \"num_rows\"
{$this->sql->from()}
{$this->sql->join()}
{$this->sql->joinAux()}
{$this->sql->conditionAll($r->getAdvanced(), $r->getSearch())}
";
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

    return "SELECT DISTINCT
{$this->sql->fieldsAll()}
{$this->sql->from()}
{$this->sql->join()}
{$this->sql->joinAux()}
WHERE
{$conditionUniqueFields}
";
  }


  //@override
  //TODO: Falta incluir condicion auxiliar
  public function unique(array $row, $render = NULL){
    $r = $this->render($render);

    $conditionUniqueFields = $this->sql->conditionUniqueFields($row);
    if(empty($conditionUniqueFields)) return null;

    return "SELECT DISTINCT
{$this->sql->fieldsAll()}
{$this->sql->from()}
{$this->sql->join()}
{$this->sql->joinAux()}
WHERE
{$conditionUniqueFields}
{$this->sql->conditionAll($r->getAdvanced(), $r->getSearch(), 'AND')}
";
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
