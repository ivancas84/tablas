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
  protected function initializeInsert(array $row) { throw new BadMethodCallException ("Metodo abstracto no implementado"); } //inicializar valores para insercion
  protected function initializeUpdate(array $row) { throw new BadMethodCallException ("Metodo abstracto no implementado"); } //inicializar valores para actualizacion
  protected function _insert(array $row) { throw new BadMethodCallException ("Metodo abstracto no implementado"); } //sql de insercion
  protected function _update(array $row) { throw new BadMethodCallException ("Metodo abstracto no implementado"); } //sql de actualizacion
  protected function format(array $row) { throw new BadMethodCallException ("Metodo abstracto no implementado"); } //formato de sql

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
    $r = $this->initializeInsert($row);
    $r_ = $this->format($r);
    $sql = $this->_insert($r_);

    return array("id" => $r["id"], "sql" => $sql, "detail"=>[$this->entity->getName().$r["id"]]);
  }

  public function update(array $row) { //sql de actualizacion

    $r = $this->initializeUpdate($row);
    $r_ = $this->format($r);
    $sql = "
{$this->_update($r_)}
WHERE id = {$row['id']};
";

    return array("id" => $r_["id"], "sql" => $sql, "detail"=>[$this->entity->getName().$r["id"]]);
  }

  public function updateAll($row, array $ids) { //sql de actualizacion para un conjunto de ids
    /**
     * Formatear valores y definir sql de actualizacion para un conjunto de ids
     * La actualizacion solo tiene en cuenta los campos definidos, los que no estan definidos, no seran considerados manteniendo su valor previo.
     * TODO: La version actual no permite actualizar el id, pero no estoy seguro por que no, cambiarlo para que permita
     * @return array("id" => "identificador principal actualizado", "sql" => "sql de actualizacion", "detail" => "detalle de campos modificados")
     */
    if(empty($ids)) throw new Exception("No existen identificadores definidos");
    $r = $this->initializeUpdate($row);
    $r_ = $this->format($r);
    $sql = "
{$this->_update($r_)}
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


  public function deleteAll(array $ids) { //eliminar

    $ids_ = [];
    for($i = 0; $i < count($ids); $i++) {
      $r = $this->format(["id"=>$ids[$i]]);
      array_push($ids_, $r["id"]);
    }
    $ids_ = implode(', ', $ids_);
    $sql = "
DELETE FROM {$this->entity->sn_()}
WHERE {$this->entity->getPk()->getName()} IN ({$ids_});
";

    array_walk($ids, function(&$item) { $item = $this->entity->getName().$item; });
    return ["ids"=>$ids, "sql"=>$sql, "detail"=>$ids];
  }

  public function deleteRequiredAll($ids, $params){ //eliminacion relacionada a clave foranea
    /**
     * En su caso mas general, este metodo es similar a delete
     * Esta pensado para facilitar la reimplementacion en el caso de que se lo requiera
     */
    return $this->deleteAll($ids);
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
