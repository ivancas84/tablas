<?php
require_once("class/model/Sql.php");

class SedeSqlMain extends EntitySql{

  public function __construct(){
    $this->entity = new SedeEntity;
    $this->db = Dba::dbInstance();
  }

  //@override
  public function _mappingField($field){
    $p = $this->prf();
    $t = $this->prt();

    switch ($field) {
      case $p.'id': return $t.".id";
      case $p.'numero': return $t.".numero";
      case $p.'nombre': return $t.".nombre";
      case $p.'organizacion': return $t.".organizacion";
      case $p.'observaciones': return $t.".observaciones";
      case $p.'alta': return $t.".alta";
      case $p.'baja': return $t.".baja";
      case $p.'usuario': return $t.".usuario";
      case $p.'estado': return $t.".estado";
      case $p.'apertura': return $t.".apertura";
      case $p.'comisiones': return $t.".comisiones";
      case $p.'domicilio': return $t.".domicilio";
      case $p.'coordinador': return $t.".coordinador";
      default: return null;
    }
  }

  //@override
  public function mappingField($field){
    if($f = $this->_mappingField($field)) return $f;
    if($f = Dba::sql('domicilio', 'dom')->_mappingField($field)) return $f;
    if($f = Dba::sql('id_persona', 'coo')->_mappingField($field)) return $f;
    if($f = Dba::sql('alumno', 'coo_alumper')->_mappingField($field)) return $f;
    throw new Exception("Campo no reconocido " . $field);
  }

  public function fields(){
    //No todos los campos se extraen de la entidad, por eso es necesario mapearlos
    $p = $this->prf();
    return '
' . $this->_mappingField($p.'id') . ' AS ' . $p.'id,
' . $this->_mappingField($p.'numero') . ' AS ' . $p.'numero,
' . $this->_mappingField($p.'nombre') . ' AS ' . $p.'nombre,
' . $this->_mappingField($p.'organizacion') . ' AS ' . $p.'organizacion,
' . $this->_mappingField($p.'observaciones') . ' AS ' . $p.'observaciones,
' . $this->_mappingField($p.'alta') . ' AS ' . $p.'alta,
' . $this->_mappingField($p.'baja') . ' AS ' . $p.'baja,
' . $this->_mappingField($p.'usuario') . ' AS ' . $p.'usuario,
' . $this->_mappingField($p.'estado') . ' AS ' . $p.'estado,
' . $this->_mappingField($p.'apertura') . ' AS ' . $p.'apertura,
' . $this->_mappingField($p.'comisiones') . ' AS ' . $p.'comisiones,
' . $this->_mappingField($p.'domicilio') . ' AS ' . $p.'domicilio,
' . $this->_mappingField($p.'coordinador') . ' AS ' . $p.'coordinador';
  }

    public function fieldsFull(){
    $fields = $this->fields() . ',
' . Dba::sql('domicilio', 'dom')->fields() . ',
' . Dba::sql('id_persona', 'coo')->fields() . ',
' . Dba::sql('alumno', 'coo_alumper')->fields() . '
';
    return $fields;
  }

  public function fieldsAux(){
    $fields = $this->_fieldsAux();

    if($f = Dba::sql('domicilio', 'dom')->_fieldsAux()) $fields .= concat($f, ', ', '', $fields);
    if($f = Dba::sql('id_persona', 'coo')->_fieldsAux()) $fields .= concat($f, ', ', '', $fields);
    if($f = Dba::sql('alumno', 'coo_alumper')->_fieldsAux()) $fields .= concat($f, ', ', '', $fields);
    return $fields;
  }


  //@override
  public function join(){
    return Dba::sql('domicilio', 'dom')->_join('domicilio', 'sede') . '
' . Dba::sql('id_persona', 'coo')->_join('coordinador', 'sede') . '
' . Dba::sql('alumno', 'coo_alumper')->_joinR('persona', 'coo') . '
' ;      
  }
  public function joinAux(){
    $join = "";
    if($j = $this->_joinAux()) $join .= "{$j}
";
    if ($j = Dba::sql('domicilio', 'dom')->_joinAux()) $join .= "{$j}
";
    if ($j = Dba::sql('id_persona', 'coo')->_joinAux()) $join .= "{$j}
";
    if ($j = Dba::sql('alumno', 'coo_alumper')->_joinAux()) $join .= "{$j}
";
  return $join;
  }


  //***** @override *****
  public function conditionSearch($search = ""){
    if(empty($search)) return '';
    $condition = $this->_conditionSearch($search) . "
 OR " . Dba::sql('domicilio', 'dom')->_conditionSearch($search) . "
 OR " . Dba::sql('id_persona', 'coo')->_conditionSearch($search) . "
 OR " . Dba::sql('alumno', 'coo_alumper')->_conditionSearch($search);
    return "(" . $condition . ")";
  }


  //***** @override *****
  public function _conditionSearch($search = ""){
    if(empty($search)) return '';
    $p = $this->prf();
    $condition = "";

    $field = $this->_mappingField($p.'id');
    $condition .= "" . $this->_conditionNumberApprox($field, $search);
    $field = $this->_mappingField($p.'numero');
    $condition .= " OR " . $this->_conditionTextApprox($field, $search);
    $field = $this->_mappingField($p.'nombre');
    $condition .= " OR " . $this->_conditionTextApprox($field, $search);
    $field = $this->_mappingField($p.'organizacion');
    $condition .= " OR " . $this->_conditionTextApprox($field, $search);
    $field = $this->_mappingField($p.'observaciones');
    $condition .= " OR " . $this->_conditionTextApprox($field, $search);
    $field = $this->_mappingField($p.'alta');
    $condition .= " OR " . $this->_conditionTimestampApprox($field, $search);
    $field = $this->_mappingField($p.'baja');
    $condition .= " OR " . $this->_conditionTimestampApprox($field, $search);
    $field = $this->_mappingField($p.'usuario');
    $condition .= " OR " . $this->_conditionNumberApprox($field, $search);
    $field = $this->_mappingField($p.'estado');
    $condition .= " OR " . $this->_conditionTextApprox($field, $search);
    $field = $this->_mappingField($p.'comisiones');
    $condition .= " OR " . $this->_conditionNumberApprox($field, $search);
    return "(" . $condition . ")";
  }

  //@override
  public function _conditionAdvanced($field, $option, $value){
    $p = $this->prf();

    $f = $this->_mappingField($field);
    switch ($field){
      case "{$p}id": return $this->conditionNumber($f, $value, $option);
      case "{$p}numero": return $this->conditionText($f, $value, $option);
      case "{$p}nombre": return $this->conditionText($f, $value, $option);
      case "{$p}organizacion": return $this->conditionText($f, $value, $option);
      case "{$p}observaciones": return $this->conditionText($f, $value, $option);
      case "{$p}usuario": return $this->conditionNumber($f, $value, $option);
      case "{$p}estado": return $this->conditionText($f, $value, $option);
      case "{$p}apertura": return $this->conditionBoolean($f, $value);
      case "{$p}comisiones": return $this->conditionNumber($f, $value, $option);
      case "{$p}domicilio": return $this->conditionNumber($f, $value, $option);
      case "{$p}coordinador": return $this->conditionNumber($f, $value, $option);
    }
  }

  //@override
  protected function conditionAdvancedMain($field, $option, $value) {
    if($c = $this->_conditionAdvanced($field, $option, $value)) return $c;
    if($c = Dba::sql('domicilio','dom')->_conditionAdvanced($field, $option, $value)) return $c;
    if($c = Dba::sql('id_persona','coo')->_conditionAdvanced($field, $option, $value)) return $c;
    if($c = Dba::sql('alumno','coo_alumper')->_conditionAdvanced($field, $option, $value)) return $c;
    throw new Exception("No pudo definirse la condicion avanzada {$field} {$option} {$value}");
  }

  //@override
  public function conditionAux() {
    $sqlCond = $this->_conditionAux();
    if($cond = Dba::sql('domicilio', 'dom')->_conditionAux()) $sqlCond .= concat($cond, ' AND', '', $sqlCond);
    if($cond = Dba::sql('id_persona', 'coo')->_conditionAux()) $sqlCond .= concat($cond, ' AND', '', $sqlCond);
    if($cond = Dba::sql('alumno', 'coo_alumper')->_conditionAux()) $sqlCond .= concat($cond, ' AND', '', $sqlCond);
    return (empty($sqlCond)) ? '' : "({$sqlCond})";
  }


}
