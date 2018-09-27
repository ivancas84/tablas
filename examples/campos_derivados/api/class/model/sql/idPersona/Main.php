<?php
require_once("class/model/Sql.php");

class IdPersonaSqlMain extends EntitySql{

  public function __construct(){
    $this->entity = new IdPersonaEntity;
    $this->db = Dba::dbInstance();
  }

  //@override
  public function _mappingField($field){
    $p = $this->prf();
    $t = $this->prt();

    switch ($field) {
      case $p.'id': return $t.".id";
      case $p.'nombres': return $t.".nombres";
      case $p.'apellidos': return $t.".apellidos";
      case $p.'sobrenombre': return $t.".sobrenombre";
      case $p.'fecha_nacimiento': return $t.".fecha_nacimiento";
      case $p.'tipo_documento': return $t.".tipo_documento";
      case $p.'numero_documento': return $t.".numero_documento";
      case $p.'email': return $t.".email";
      case $p.'genero': return $t.".genero";
      case $p.'cuil': return $t.".cuil";
      case $p.'alta': return $t.".alta";
      case $p.'telefonos': return $t.".telefonos";
      default: return null;
    }
  }

  //@override
  public function mappingField($field){
    if($f = $this->_mappingField($field)) return $f;
    if($f = Dba::sql('alumno', 'alumper')->_mappingField($field)) return $f;
    throw new Exception("Campo no reconocido " . $field);
  }

  public function fields(){
    //No todos los campos se extraen de la entidad, por eso es necesario mapearlos
    $p = $this->prf();
    return '
' . $this->_mappingField($p.'id') . ' AS ' . $p.'id,
' . $this->_mappingField($p.'nombres') . ' AS ' . $p.'nombres,
' . $this->_mappingField($p.'apellidos') . ' AS ' . $p.'apellidos,
' . $this->_mappingField($p.'sobrenombre') . ' AS ' . $p.'sobrenombre,
' . $this->_mappingField($p.'fecha_nacimiento') . ' AS ' . $p.'fecha_nacimiento,
' . $this->_mappingField($p.'tipo_documento') . ' AS ' . $p.'tipo_documento,
' . $this->_mappingField($p.'numero_documento') . ' AS ' . $p.'numero_documento,
' . $this->_mappingField($p.'email') . ' AS ' . $p.'email,
' . $this->_mappingField($p.'genero') . ' AS ' . $p.'genero,
' . $this->_mappingField($p.'cuil') . ' AS ' . $p.'cuil,
' . $this->_mappingField($p.'alta') . ' AS ' . $p.'alta,
' . $this->_mappingField($p.'telefonos') . ' AS ' . $p.'telefonos';
  }

    public function fieldsFull(){
    $fields = $this->fields() . ',
' . Dba::sql('alumno', 'alumper')->fields() . '
';
    return $fields;
  }

  public function fieldsAux(){
    $fields = $this->_fieldsAux();

    if($f = Dba::sql('alumno', 'alumper')->_fieldsAux()) $fields .= concat($f, ', ', '', $fields);
    return $fields;
  }


  //@override
  public function join(){
    return Dba::sql('alumno', 'alumper')->_joinR('persona', 'ip') . '
' ;      
  }
  public function joinAux(){
    $join = "";
    if($j = $this->_joinAux()) $join .= "{$j}
";
    if ($j = Dba::sql('alumno', 'alumper')->_joinAux()) $join .= "{$j}
";
  return $join;
  }


  //***** @override *****
  public function conditionSearch($search = ""){
    if(empty($search)) return '';
    $condition = $this->_conditionSearch($search) . "
 OR " . Dba::sql('alumno', 'alumper')->_conditionSearch($search);
    return "(" . $condition . ")";
  }


  //***** @override *****
  public function _conditionSearch($search = ""){
    if(empty($search)) return '';
    $p = $this->prf();
    $condition = "";

    $field = $this->_mappingField($p.'id');
    $condition .= "" . $this->_conditionNumberApprox($field, $search);
    $field = $this->_mappingField($p.'nombres');
    $condition .= " OR " . $this->_conditionTextApprox($field, $search);
    $field = $this->_mappingField($p.'apellidos');
    $condition .= " OR " . $this->_conditionTextApprox($field, $search);
    $field = $this->_mappingField($p.'sobrenombre');
    $condition .= " OR " . $this->_conditionTextApprox($field, $search);
    $field = $this->_mappingField($p.'fecha_nacimiento');
    $condition .= " OR " . $this->_conditionDateApprox($field, $search);
    $field = $this->_mappingField($p.'tipo_documento');
    $condition .= " OR " . $this->_conditionTextApprox($field, $search);
    $field = $this->_mappingField($p.'numero_documento');
    $condition .= " OR " . $this->_conditionTextApprox($field, $search);
    $field = $this->_mappingField($p.'email');
    $condition .= " OR " . $this->_conditionTextApprox($field, $search);
    $field = $this->_mappingField($p.'genero');
    $condition .= " OR " . $this->_conditionTextApprox($field, $search);
    $field = $this->_mappingField($p.'cuil');
    $condition .= " OR " . $this->_conditionTextApprox($field, $search);
    $field = $this->_mappingField($p.'alta');
    $condition .= " OR " . $this->_conditionTimestampApprox($field, $search);
    $field = $this->_mappingField($p.'telefonos');
    $condition .= " OR " . $this->_conditionTextApprox($field, $search);
    return "(" . $condition . ")";
  }

  //@override
  public function _conditionAdvanced($field, $option, $value){
    $p = $this->prf();

    $f = $this->_mappingField($field);
    switch ($field){
      case "{$p}id": return $this->conditionNumber($f, $value, $option);
      case "{$p}nombres": return $this->conditionText($f, $value, $option);
      case "{$p}apellidos": return $this->conditionText($f, $value, $option);
      case "{$p}sobrenombre": return $this->conditionText($f, $value, $option);
      case "{$p}fecha_nacimiento": return $this->conditionDate($f, $value, $option);
      case "{$p}tipo_documento": return $this->conditionText($f, $value, $option);
      case "{$p}numero_documento": return $this->conditionText($f, $value, $option);
      case "{$p}email": return $this->conditionText($f, $value, $option);
      case "{$p}genero": return $this->conditionText($f, $value, $option);
      case "{$p}cuil": return $this->conditionText($f, $value, $option);
      case "{$p}telefonos": return $this->conditionText($f, $value, $option);
    }
  }

  //@override
  protected function conditionAdvancedMain($field, $option, $value) {
    if($c = $this->_conditionAdvanced($field, $option, $value)) return $c;
    if($c = Dba::sql('alumno','alumper')->_conditionAdvanced($field, $option, $value)) return $c;
    throw new Exception("No pudo definirse la condicion avanzada {$field} {$option} {$value}");
  }

  //@override
  public function conditionAux() {
    $sqlCond = $this->_conditionAux();
    if($cond = Dba::sql('alumno', 'alumper')->_conditionAux()) $sqlCond .= concat($cond, ' AND', '', $sqlCond);
    return (empty($sqlCond)) ? '' : "({$sqlCond})";
  }


}
