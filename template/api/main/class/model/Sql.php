<?php

require_once("function/snake_case_to.php");
require_once("function/concat.php");
require_once("function/settypebool.php");
require_once("class/db/Interface.php");


//Facilitar la definicion de sql
//Para definir el sql se deben utilizar algunos métodos que requieren una conexion abierta a la base de datos, como por ejemplo "escapar caracteres"
//Facilitar la generacion de consultas SQL a traves de una serie de metodos que son de uso comun para todas las consultas
//Esta clase ofrece soporte y traduccion para motores MySql (prioritario) y Postgresql. Si se dificulta la traduccion para motores no prioritarios entonces no se definen los metodos
abstract class EntitySql {

  protected $entity; //Entity. Configuracion de la tabla
  protected $db;
    //Para definir el sql es necesaria la existencia de una clase de acceso abierta, ya que ciertos metodos, como por ejemplo "escapar caracteres" lo requieren.
    //Ademas, ciertos metodos requieren determinar el motor de base de datos para definir la sintaxis SQL adecuada

  public function _mappingField($field){ throw new BadMethodCallException("Not Implemented"); }

  public function mappingField($field){
    $field_ = $this->_mappingField($field);
    if(!$field_) throw new Exception("Campo no reconocido");
    return $field_;
  }

  //Todos los metodos para obtener los fields de uso habitual se encuentran reunidos en uno solo
  //@param $filter Solo se devolveran los campos definidos en el filtro
  //@param $fieldsAdd Se adicionan los fields al conjunto devuelto
  public function fieldsAll($filter = null, $fieldsAdd = null) {
    if(!empty($filter)) $fields = $this->sql->filterFields($filter);
    $fields = $this->sql->fieldsFull();
    if(!empty($this->sql->fieldsAux())) $fields .= ",
" . $this->sql->fieldsAux();
    return $fields;
  }


  public function filterFields(array $fields = null){
    if(empty($fields)) return false;

    $fields_ = [];
    foreach ($fields as $field){
      $field_ = $this->mappingField($field);
      array_push($fields_, $field_ . " AS " . $field);
    }

    return implode(", ", $fields_);
  }


  //***** metodos abstractos *****
  public function conditionSearch($search = "") {  } //Definir condicion de busqueda simple

  //Definir sql condicion obligatoria
  //Utilizada generalmente para restringir visualización, CUIDADO CON LA PERSISTENCIA!!! las restricciones de visualización son también aplicadas al persistir, pudiendo no tener el efecto deseado.
  //@return "(condition) " //por las dudas dejar espacio despues de condicion
  //@example "(alias.field = value) "
  //@example "(pago.deleted = false) "
  public function conditionAux(){ throw new BadMethodCallException("Not Implemented");  } //Llamar a cadena de metodos independientes


  public function _conditionAux($prefix = ''){ return "";  } //Sobrescribir si existe condicion obligatoria


  //Busqueda avanzada
  //@param   Array $advanced
  //  [
  //    0 => "field"
  //    1 => "=", "!=", ">=", "<=", "<", ">", ...
  //    2 => "value" array|string|int|boolean|date (si es null no se define busqueda, si es un array se definen tantas busquedas como elementos tenga el array)
  //    3 => "AND" | "OR" | null (opcional, por defecto AND)
  //  ]
  //  Array(
  //    Array("field" => "field", "value" => array|string|int|boolean|date (si es null no se define busqueda, si es un array se definen tantas busquedas como elementos tenga el array) [, "option" => "="|"=~"|"!="|"<"|"<="|">"|">="|true (no nulos)|false (nulos)][, "mode" => "and"|"or"]
  //    ...
  //  )
  //  )
  public function conditionAdvanced(array $advanced) {
    if(!count($advanced)) return "";
    $conditionMode = $this->conditionAdvancedRecursive($advanced);
    return $conditionMode["condition"];
  }

  public function conditionAdvancedRecursive(array $advanced){
    if(!is_array($advanced[0])) {
      $mode = (empty($advanced[3])) ? "AND" : $advanced[3];
      $condicion = $this->conditionAdvancedMain($advanced[0], $advanced[1], $advanced[2], "");
      return ["condition" => $condicion, "mode" => $mode];

    } else {
      return $this->conditionAdvancedIterable($advanced) ;
    }
  }

  protected function conditionAdvancedIterable(array $advanced) {
    $conditionModes = array();

    for($i = 0; $i < count($advanced); $i++){
      $conditionMode = $this->conditionAdvancedRecursive($advanced[$i]);
      array_push($conditionModes, $conditionMode);
    }

    $modeReturn = $conditionModes[0]["mode"];
    $condition = "";

    foreach($conditionModes as $cm){
      $mode = $cm["mode"];
      if(!empty($condition)) $condition .= $mode . " ";
      $condition.= $cm["condition"];
    }

    return ["condition"=>"(".$condition.")", "mode"=>$modeReturn];
  }


  protected function conditionAdvancedMain($field, $option, $value){ throw new BadMethodCallException("Not Implemented"); } //Definir sql con los campos de la tabla principal

  public function _fields(){ throw new BadMethodCallException("Not Implemented"); } //Definir sql con los campos

  public function fieldId(){ return $this->entity->getAlias() . "." . $this->entity->getPk()->getName(); } //Se define el identificador en un metodo independiente para facilitar la reimplementacion para aquellos casos en que el id tenga un nombre diferente al requerido, para el framework es obligatorio que todas las entidades tengan una pk con nombre "id"

  public function from(){
    return " FROM " . $this->entity->getSna_() . "
";
  }

  public function limit($page = 1, $size = false){
    if ($size) {
      return " LIMIT {$size} OFFSET " . ( ($page - 1) * $size ) . "
";
    }
    return "";
  }

  //***** Definir condicion de busqueda de texto aproximada (en base al motor de base de datos define la sintaxis correspondiente *****
  protected function _conditionTextApprox($field, $value){
    return "(lower(" . $field . ") LIKE lower('%" . $value . "%'))";
  }

  //***** Definir condicion de busqueda de texto aproximada (en base al motor de base de datos define la sintaxis correspondiente *****
  protected function _conditionDateApprox($field, $value){
    if($this->db->getDbms() == "mysql") return "(CAST(DATE_FORMAT(" . $field . ", '%d/%m/%Y') AS CHAR) LIKE '%" . $value . "%' )";
    else return "(TO_CHAR(" . $field . ", 'DD/MM/YYYY') LIKE '%" . $value . "%' )";
  }

   //***** Definir condicion de busqueda de texto aproximada (en base al motor de base de datos define la sintaxis correspondiente *****
  protected function _conditionDate($field, $value, $option){
    if($this->db->getDbms() == "mysql") return "(" . $field . " " . $option. " '" . $value . "')";
    else return "(" . $field . " " . $option. " TO_DATE('" . $value . "', 'YYYY-MM-DD') )";
  }




  protected function _conditionNumberApprox($field, $value){
    if($this->db->getDbms() == "mysql") return "(CAST(" . $field . " AS CHAR) LIKE '%" . $value . "%' )";
    else return "(trim(both ' ' from to_char(" . $field . ", '99999999999999999999')) LIKE '%" . $value . "%' ) ";
  }


  protected function _conditionTimestampApprox($field, $value){
    if($this->db->getDbms() == "mysql") return "(CAST(DATE_FORMAT(" . $field . ", '%d/%m/%Y %H:%i') AS CHAR) LIKE '%" . $value . "%' )";
    else return "(TO_CHAR(" . $field . ", 'DD/MM/YYYY HH:MI') LIKE '%" . $value . "%' )";
  }

  protected function _conditionYearApprox($field, $value){
    if($this->db->getDbms() == "mysql") return "(CAST(DATE_FORMAT(" . $field . ", '%Y') AS CHAR) LIKE '%" . $value . "%' )";
    else return "(TO_CHAR(" . $field . ", 'YYYY') LIKE '%" . $value . "%' )";
  }


  //***** Definir condicion de busqueda de texto *****
  protected function conditionText($field, $value, $option = "="){
    if($value === false) return "(" . $field . " IS NULL) ";
    if($value === true) return "(" . $field . " IS NOT NULL) ";
    if($option == "=~") return $this->_conditionTextApprox($field, $value);
    return "(lower(" . $field . ") " . $option . " lower('" . $value . "')) ";
  }

  protected function conditionDate($field, $value, $option = "="){
    if($value === false) return "(" . $field . " IS NULL) ";
    if($value === true) return "(" . $field . " IS NOT NULL) ";
    return $this->_conditionDate($field, $value, $option);



  }

  protected function conditionNumber($field, $value, $option = "="){
    if($value === false) return "(" . $field . " IS NULL) ";
    if($value === true) return "(" . $field . " IS NOT NULL) ";
    if($option === true) return "(" . $field . " IS NULL) ";
    if($option === false) return "(" . $field . " IS NOT NULL) ";
    return "(" . $field . " " . $option . " " . $value . ") ";
  }

  //***** Definir condicion de busqueda de booleano *****
  protected function conditionBoolean($field, $value = NULL){
    $v = (settypebool($value)) ? "true" : "false";
    return "(" . $field . " = " . $v . ") ";
  }


  //Definir todas las condiciones
  public function conditionAll(array $advanced = null, $search = null, $connect="WHERE") {
    $sqlCond = concat($this->conditionSearch($search), $connect);
    $sqlCond .= concat($this->conditionAdvanced($advanced), " AND", $connect, $sqlCond);
    $sqlCond .= concat($this->conditionAux(), " AND", $connect, $sqlCond);
    return $sqlCond;
  }


  //Filtrar campos unicos y definir condicion
  public function conditionUniqueFields(array $fields){
    $uniqueFields = $this->entity->getFieldsUnique();

    $advancedSearch = array();
    foreach($fields as $key => $value){
      foreach($uniqueFields as $field){
        if($key == $field->getName()) {
          if(!empty($value)) array_push($advancedSearch, [$key, "=", $value, "or"]);
        }
      }
    }

    return $this->conditionAdvanced($advancedSearch);
  }




  //Definir sql de campos
  public function fieldsFull(){ return $this->fields(); } //sobrescribir si existen relaciones



  //Definir etiqueta (concatenar campos principales)
  //Puede requerir relaciones completas!!!
  public function fieldsLabel(){
    return $this->entity->getAlias() . "." . $this->entity->getPk()->getName() . " AS label
";
  }

  //Definir etiqueta (concatenar campos principales)
  //Sin relaciones
  public function _fieldsLabel($prefix){
    $t = (empty($prefix)) ?  'asig'  : $prefix;
    $p = (empty($prefix)) ?  ''  : $prefix . '_';

    return "{$t}." . $this->entity->getPk()->getName() . " AS {$p}label
";
  }

  //Definir sql de campos de cadena de relaciones
  public function fieldsLabelFull(){ return ""; }  //sobrescribir si existen relaciones y son consideradas como campos principales

  //Definir sql con campos auxiliares
  public function fieldsAux(){ return ""; } //sobrescribir si se desean campos auxiliares

  //Definir sql con cadena de relaciones fk y u_
  public function join(){ return ""; } //Sobrescribir si existen relaciones fk u_

  //Definir sql con relacion auxiliar
  //Utilizada generalmente para restringir visualización, CUIDADO CON LA PERSISTENCIA!!! Las restricciones de visualización son también aplicadas al persistir, pudiendo no tener el efecto deseado.
  public function joinAux(){ return ""; } //Sobrescribir si existe relacion auxiliar

  //Ordenamiento de cadena de relaciones
  public function orderBy(array $order = null){
    if(empty($order)) return "";

    $sql = '';

    foreach($order as $key => $value){
      $value = ((strtolower($value) == "asc") || ($value === true)) ? "asc" : "desc";
      $sql_ = $key . " IS NULL, ";
      $sql_ .= $key . " " . $value;
      $sql .= concat($sql_, ', ', ' ORDER BY', $sql);
    }
    return $sql;
  }


  //Ordenamiento de cadena de relaciones (metodo nuevo no independiente que reemplazara al orderBy, el orderBy sera redefinido en las subclases para facilitar el soporte a pg
  protected function _order($field, $value){
    $value = ((strtolower($value) == "asc") || ($value === true)) ? "asc" : "desc";
    return "{$field} IS NULL, {$field} {$value}";
  }



  public function isUpdatable(array $row){
    try {
      $row_ = $this->initializeUpdateSql($row);
      $this->formatSql($row_);
      return true;
    } catch(Exception $exception){
      return $exception->getMessage();
    }
  }

  public function isInsertable(array $row){
    try {
      $row_ = $this->initializeInsertSql($row);
      $this->formatSql($row_);
      return true;
    } catch(Exception $exception){
      return $exception->getMessage();
    }
  }






  //Definir valor numerico para la base de datos
  //@param mixed $value Valor a definir.
  //  'null': Valor especial que indica que el campo debe definirse en null
  //@throws Exception si value no se encuentra correctamente definido
  public function numeric($value){
    if(is_null($value) || ($value === 'null')) return 'null';

    if ( !is_numeric($value) ) throw new Exception('Valor numerico incorrecto: ' . $value);
    else return $value;
  }

  //Definir valor numerico entero mayor a 0 para la base de datos
  //@param $value Valor a definir.
  //  'null': Valor especial que indica que el campo debe definirse en null
  //@throws Exception si value no se encuentra correctamente definido
  public function positiveIntegerWithoutZerofill($value){
    if(is_null($value) || ($value === 'null')) return 'null';

    if ((!is_numeric($value)) && (!intval($value) > 0)) throw new Exception('Valor entero positivo sin ceros incorrecto: ' . $value);
    else return $value;
  }

  //Definir valor timestamp para la base de datos
  //@param $value Valor a definir.
  //  'null': Valor especial que indica que el campo debe definirse en null
  //@throws Exception si value no se encuentra correctamente definido
  public function timestamp($value){
    if($value == 'null') return 'null';

    if(is_object($value) && get_class($value) == "DateTime"){
      $datetime = $value;
    } else {
      $datetime = DateTime::createFromFormat('Y-m-d H:i:s', $value);
    }

    if ( !$datetime ) throw new Exception('Valor fecha y hora incorrecto: ' . $value);
    else return "'" . $datetime->format('Y-m-d H:i:s') . "'";
  }

  //Definir valor date para la base de datos
  //@param $value Valor a definir.
  //  'null': Valor especial que indica que el campo debe definirse en null
  //@throws Exception si value no se encuentra correctamente definido
  public function date($value){
    if($value == 'null') return 'null';

    if(is_object($value) && get_class($value) == "DateTime"){
      $datetime = $value;
    } else {
      $datetime = DateTime::createFromFormat('Y-m-d', $value);
    }

    if ( !$datetime ) throw new Exception('Valor fecha incorrecto: ' . $value);
    else return "'" . $datetime->format('Y-m-d') . "'";
  }


  //Definir valor time para la base de datos
  //@param $value Valor a definir.
  //  'null': Valor especial que indica que el campo debe definirse en null
  //@throws Exception si value no se encuentra correctamente definido
  public function time($value){
    if($value == 'null') return 'null';

    if(is_object($value) && get_class($value) == "DateTime"){
      $datetime = $value;
    } else {
      $datetime = DateTime::createFromFormat('H:i', $value);
    }

    if ( !$datetime ) throw new Exception('Valor fecha incorrecto: ' . $value);
    else return "'" . $datetime->format('H:i') . "'";
  }


  //Definir valor time para la base de datos
  //@param $value Valor a definir.
  //  'null': Valor especial que indica que el campo debe definirse en null
  //@throws Exception si value no se encuentra correctamente definido
  public function year($value){
    if($value == 'null') return 'null';

    if(is_object($value) && get_class($value) == "DateTime"){
      $datetime = $value;
    } else {
      $datetime = DateTime::createFromFormat('Y', $value);
    }

    if ( !$datetime ) throw new Exception('Valor año incorrecto: ' . $value);
    else return "'" . $datetime->format('Y') . "'";
  }



  //Definir valor boolean para la base de datos
  //@param $value Valor a definir. 'null': Valor especial que indica que el campo debe definirse en null
  public function boolean($value){
    if(is_null($value) || ($value === 'null')) return 'null';

    return ( settypebool($value) ) ? 'true' : 'false';
  }



  //Definir string
  //@param string $value Valor a definir. 'null': Valor especial que indica que el campo debe definirse en null
  //@throws Exception si value no se encuentra correctamente definido
  public function string($value){
    if(is_null($value) || ($value === 'null')) return 'null';

    if (!is_string($value)) throw new Exception('Valor de caracteres incorrecto: ' . $value);
    else return "'" . $value . "'";
  }

  //Definir string
  //@param string $value Valor a definir. 'null': Valor especial que indica que el campo debe definirse en null
  //@throws Exception si value no se encuentra correctamente definido
  public function escapeString($value){
    if($value == 'null') return 'null';

    $v = (is_numeric($value)) ? strval($value) : $value;
    if (!is_string($v)) throw new Exception('Valor de caracteres incorrecto: ' . $v);
    else $escapedString = $this->db->escapeString($v);
    return "'" . $escapedString . "'";
  }

}
