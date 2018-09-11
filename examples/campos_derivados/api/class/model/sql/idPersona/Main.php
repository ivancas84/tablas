<?php
require_once("class/model/Sql.php");

class IdPersonaSqlMain extends EntitySql{

  public function __construct(){
    $this->entity = new IdPersonaEntity;
    $this->db = Dba::dbInstance();
  }

  //@override
  public function _mappingField($field, $prefix=''){
    $prf = (empty($prefix)) ? '' : $prefix . '_';
    $prt = (empty($prefix)) ? 'ip' : $prefix;

    switch ($field) {
      case $prf.'id': return $prt.".id";
      case $prf.'nombres': return $prt.".nombres";
      case $prf.'apellidos': return $prt.".apellidos";
      case $prf.'sobrenombre': return $prt.".sobrenombre";
      case $prf.'fecha_nacimiento': return $prt.".fecha_nacimiento";
      case $prf.'tipo_documento': return $prt.".tipo_documento";
      case $prf.'numero_documento': return $prt.".numero_documento";
      case $prf.'email': return $prt.".email";
      case $prf.'genero': return $prt.".genero";
      case $prf.'cuil': return $prt.".cuil";
      case $prf.'alta': return $prt.".alta";
      default: return null;
    }
  }

  //@override
  public function mappingField($field){
    $field_ = $this->_mappingField($field); if($field_) return $field_;
    $field_ = Dba::sql('alumno')->_mappingField($field, 'alumper'); if($field_) return $field_;
    throw new Exception("Campo no reconocido " . $field);
  }

  public function fields($prefix = ''){
    $p = (empty($prefix)) ?  ''  : $prefix . '_';
    return '
' . $this->_mappingField($p.'id', $prefix) . ' AS ' . $p.'id,
' . $this->_mappingField($p.'nombres', $prefix) . ' AS ' . $p.'nombres,
' . $this->_mappingField($p.'apellidos', $prefix) . ' AS ' . $p.'apellidos,
' . $this->_mappingField($p.'sobrenombre', $prefix) . ' AS ' . $p.'sobrenombre,
' . $this->_mappingField($p.'fecha_nacimiento', $prefix) . ' AS ' . $p.'fecha_nacimiento,
' . $this->_mappingField($p.'tipo_documento', $prefix) . ' AS ' . $p.'tipo_documento,
' . $this->_mappingField($p.'numero_documento', $prefix) . ' AS ' . $p.'numero_documento,
' . $this->_mappingField($p.'email', $prefix) . ' AS ' . $p.'email,
' . $this->_mappingField($p.'genero', $prefix) . ' AS ' . $p.'genero,
' . $this->_mappingField($p.'cuil', $prefix) . ' AS ' . $p.'cuil,
' . $this->_mappingField($p.'alta', $prefix) . ' AS ' . $p.'alta';
  }

    public function fieldsFull(){
    $fields = $this->fields() . ', ';
    $fields .= Dba::sql('alumno')->fields('alumper') . '';
    return $fields;
  }

  public function fieldsLabelFull(){
    $fields = '';
    $fields .= Dba::sql('alumno')->_fieldsLabel('alumper') . '';
    return $fields;
  }

  public function fieldsAux(){
    $fields = $this->_fieldsAux();

    $fields_ = Dba::sql('alumno')->_fieldsAux('alumper');
    $fields .= concat($fields_, ', ', '', $fields);

    return $fields;
  }


  //@override
  public function join(){
    $sql = '';
    $sql .= Dba::sql('alumno')->_joinR('persona', 'ip', 'alumper');
    return $sql;
  }
public function joinAux(){
    $join = $this->_joinAux() . '
';
    $sql = new AlumnoSql; $join .= $sql->_joinAux('alumper') . '
';
    return $join;
  }


  //***** @override *****
  public function conditionSearch($search = ""){
    if(empty($search)) return '';
    $condition = $this->_conditionSearch($search);

  $condition .= " OR " . Dba::sql('alumno')->_conditionSearch($search, 'alumper');
    return "(" . $condition . ")";
  }


  //***** @override *****
  public function _conditionSearch($search = "", $prefix = ""){
    if(empty($search)) return '';
    $p = ($prefix) ? $prefix . '_' : '';
    $condition = "";

    $field = $this->_mappingField($p . 'id', $prefix);
    $condition .= "" . $this->_conditionNumberApprox($field, $search);
    $field = $this->_mappingField($p . 'nombres', $prefix);
    $condition .= " OR " . $this->_conditionTextApprox($field, $search);
    $field = $this->_mappingField($p . 'apellidos', $prefix);
    $condition .= " OR " . $this->_conditionTextApprox($field, $search);
    $field = $this->_mappingField($p . 'sobrenombre', $prefix);
    $condition .= " OR " . $this->_conditionTextApprox($field, $search);
    $field = $this->_mappingField($p . 'fecha_nacimiento', $prefix);
    $condition .= " OR " . $this->_conditionDateApprox($field, $search);
    $field = $this->_mappingField($p . 'tipo_documento', $prefix);
    $condition .= " OR " . $this->_conditionTextApprox($field, $search);
    $field = $this->_mappingField($p . 'numero_documento', $prefix);
    $condition .= " OR " . $this->_conditionTextApprox($field, $search);
    $field = $this->_mappingField($p . 'email', $prefix);
    $condition .= " OR " . $this->_conditionTextApprox($field, $search);
    $field = $this->_mappingField($p . 'genero', $prefix);
    $condition .= " OR " . $this->_conditionTextApprox($field, $search);
    $field = $this->_mappingField($p . 'cuil', $prefix);
    $condition .= " OR " . $this->_conditionTextApprox($field, $search);
    $field = $this->_mappingField($p . 'alta', $prefix);
    $condition .= " OR " . $this->_conditionTimestampApprox($field, $search);
    return "(" . $condition . ")";
  }

  //@override
  public function _conditionAdvanced($field, $option, $value, $prefix = ''){
    $p = (empty($prefix)) ?  ''  : $prefix . '_';

    $f = $this->_mappingField($field, $prefix);
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
    }
  }

  //@override
  protected function conditionAdvancedMain($field, $option, $value, $prefix = '') {
    $p = (empty($prefix)) ?  ''  : $prefix . '_';

    if($c = $this->_conditionAdvanced($field, $option, $value, $prefix)) return $c;
    $sql = new AlumnoSql; if($c = $sql->_conditionAdvanced($field, $option, $value, $p.'alumper')) return $c;
    throw new Exception("No pudo definirse la condicion avanzada {$field} {$option} {$value}");
  }

  //@override
  public function conditionAux() {
    $sqlCond = $this->_conditionAux();
    $sql = new AlumnoSql; $cond = $sql->_conditionAux('alumper');
    $sqlCond .= concat($cond, ' AND', '', $sqlCond);

    return (empty($sqlCond)) ? '' : "({$sqlCond})";
  }


}
