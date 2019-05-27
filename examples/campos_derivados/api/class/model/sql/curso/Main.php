<?php
require_once("class/model/Sql.php");

class CursoSqlMain extends EntitySql{

  public function __construct(){
    $this->entity = new CursoEntity;
    $this->db = Dba::dbInstance();
  }

  //@override
  public function _mappingField($field){
    $p = $this->prf();
    $t = $this->prt();

    switch ($field) {
      case $p.'id': return $t.".id";
      case $p.'estado': return $t.".estado";
      case $p.'alta': return $t.".alta";
      case $p.'baja': return $t.".baja";
      case $p.'horario': return $t.".horario";
      case $p.'comision': return $t.".comision";
      case $p.'carga_horaria': return $t.".carga_horaria";
      case $p.'profesor_activo': return $t.".profesor_activo";
      default: return null;
    }
  }

  //@override
  public function mappingField($field){
    if($f = $this->_mappingField($field)) return $f;
    if($f = EntitySql::getInstanceFromString('comision', 'com')->_mappingField($field)) return $f;
    if($f = EntitySql::getInstanceFromString('division', 'com_dvi')->_mappingField($field)) return $f;
    if($f = EntitySql::getInstanceFromString('plan', 'com_dvi_pla')->_mappingField($field)) return $f;
    if($f = EntitySql::getInstanceFromString('sede', 'com_dvi_sed')->_mappingField($field)) return $f;
    if($f = EntitySql::getInstanceFromString('domicilio', 'com_dvi_sed_dom')->_mappingField($field)) return $f;
    if($f = EntitySql::getInstanceFromString('id_persona', 'com_dvi_sed_coo')->_mappingField($field)) return $f;
    if($f = EntitySql::getInstanceFromString('alumno', 'com_dvi_sed_coo_alumper')->_mappingField($field)) return $f;
    if($f = EntitySql::getInstanceFromString('carga_horaria', 'ch')->_mappingField($field)) return $f;
    if($f = EntitySql::getInstanceFromString('asignatura', 'ch_asi')->_mappingField($field)) return $f;
    if($f = EntitySql::getInstanceFromString('plan', 'ch_pla')->_mappingField($field)) return $f;
    if($f = EntitySql::getInstanceFromString('id_persona', 'pa')->_mappingField($field)) return $f;
    if($f = EntitySql::getInstanceFromString('alumno', 'pa_alumper')->_mappingField($field)) return $f;
    throw new Exception("Campo no reconocido " . $field);
  }

  public function fields(){
    //No todos los campos se extraen de la entidad, por eso es necesario mapearlos
    $p = $this->prf();
    return '
' . $this->_mappingField($p.'id') . ' AS ' . $p.'id,
' . $this->_mappingField($p.'estado') . ' AS ' . $p.'estado,
' . $this->_mappingField($p.'alta') . ' AS ' . $p.'alta,
' . $this->_mappingField($p.'baja') . ' AS ' . $p.'baja,
' . $this->_mappingField($p.'horario') . ' AS ' . $p.'horario,
' . $this->_mappingField($p.'comision') . ' AS ' . $p.'comision,
' . $this->_mappingField($p.'carga_horaria') . ' AS ' . $p.'carga_horaria,
' . $this->_mappingField($p.'profesor_activo') . ' AS ' . $p.'profesor_activo';
  }

    public function fieldsFull(){
    $fields = $this->fields() . ',
' . EntitySql::getInstanceFromString('comision', 'com')->fields() . ',
' . EntitySql::getInstanceFromString('comision', 'com_cs')->fields() . ',
' . EntitySql::getInstanceFromString('division', 'com_cs_dvi')->fields() . ',
' . EntitySql::getInstanceFromString('plan', 'com_cs_dvi_pla')->fields() . ',
' . EntitySql::getInstanceFromString('sede', 'com_cs_dvi_sed')->fields() . ',
' . EntitySql::getInstanceFromString('domicilio', 'com_cs_dvi_sed_dom')->fields() . ',
' . EntitySql::getInstanceFromString('id_persona', 'com_cs_dvi_sed_coo')->fields() . ',
' . EntitySql::getInstanceFromString('alumno', 'com_cs_dvi_sed_coo_alumper')->fields() . ',
' . EntitySql::getInstanceFromString('division', 'com_dvi')->fields() . ',
' . EntitySql::getInstanceFromString('plan', 'com_dvi_pla')->fields() . ',
' . EntitySql::getInstanceFromString('sede', 'com_dvi_sed')->fields() . ',
' . EntitySql::getInstanceFromString('domicilio', 'com_dvi_sed_dom')->fields() . ',
' . EntitySql::getInstanceFromString('id_persona', 'com_dvi_sed_coo')->fields() . ',
' . EntitySql::getInstanceFromString('alumno', 'com_dvi_sed_coo_alumper')->fields() . ',
' . EntitySql::getInstanceFromString('carga_horaria', 'ch')->fields() . ',
' . EntitySql::getInstanceFromString('asignatura', 'ch_asi')->fields() . ',
' . EntitySql::getInstanceFromString('plan', 'ch_pla')->fields() . ',
' . EntitySql::getInstanceFromString('id_persona', 'pa')->fields() . ',
' . EntitySql::getInstanceFromString('alumno', 'pa_alumper')->fields() . '
';
    return $fields;
  }

  public function fieldsAux(){
    $fields = $this->_fieldsAux();

    if($f = EntitySql::getInstanceFromString('comision', 'com')->_fieldsAux()) $fields .= concat($f, ', ', '', $fields);
    if($f = EntitySql::getInstanceFromString('comision', 'com_cs')->_fieldsAux()) $fields .= concat($f, ', ', '', $fields);
    if($f = EntitySql::getInstanceFromString('division', 'com_cs_dvi')->_fieldsAux()) $fields .= concat($f, ', ', '', $fields);
    if($f = EntitySql::getInstanceFromString('plan', 'com_cs_dvi_pla')->_fieldsAux()) $fields .= concat($f, ', ', '', $fields);
    if($f = EntitySql::getInstanceFromString('sede', 'com_cs_dvi_sed')->_fieldsAux()) $fields .= concat($f, ', ', '', $fields);
    if($f = EntitySql::getInstanceFromString('domicilio', 'com_cs_dvi_sed_dom')->_fieldsAux()) $fields .= concat($f, ', ', '', $fields);
    if($f = EntitySql::getInstanceFromString('id_persona', 'com_cs_dvi_sed_coo')->_fieldsAux()) $fields .= concat($f, ', ', '', $fields);
    if($f = EntitySql::getInstanceFromString('alumno', 'com_cs_dvi_sed_coo_alumper')->_fieldsAux()) $fields .= concat($f, ', ', '', $fields);
    if($f = EntitySql::getInstanceFromString('division', 'com_dvi')->_fieldsAux()) $fields .= concat($f, ', ', '', $fields);
    if($f = EntitySql::getInstanceFromString('plan', 'com_dvi_pla')->_fieldsAux()) $fields .= concat($f, ', ', '', $fields);
    if($f = EntitySql::getInstanceFromString('sede', 'com_dvi_sed')->_fieldsAux()) $fields .= concat($f, ', ', '', $fields);
    if($f = EntitySql::getInstanceFromString('domicilio', 'com_dvi_sed_dom')->_fieldsAux()) $fields .= concat($f, ', ', '', $fields);
    if($f = EntitySql::getInstanceFromString('id_persona', 'com_dvi_sed_coo')->_fieldsAux()) $fields .= concat($f, ', ', '', $fields);
    if($f = EntitySql::getInstanceFromString('alumno', 'com_dvi_sed_coo_alumper')->_fieldsAux()) $fields .= concat($f, ', ', '', $fields);
    if($f = EntitySql::getInstanceFromString('carga_horaria', 'ch')->_fieldsAux()) $fields .= concat($f, ', ', '', $fields);
    if($f = EntitySql::getInstanceFromString('asignatura', 'ch_asi')->_fieldsAux()) $fields .= concat($f, ', ', '', $fields);
    if($f = EntitySql::getInstanceFromString('plan', 'ch_pla')->_fieldsAux()) $fields .= concat($f, ', ', '', $fields);
    if($f = EntitySql::getInstanceFromString('id_persona', 'pa')->_fieldsAux()) $fields .= concat($f, ', ', '', $fields);
    if($f = EntitySql::getInstanceFromString('alumno', 'pa_alumper')->_fieldsAux()) $fields .= concat($f, ', ', '', $fields);
    return $fields;
  }


  //@override
  public function join(){
    return EntitySql::getInstanceFromString('comision', 'com')->_join('comision', 'curs') . '
' . EntitySql::getInstanceFromString('comision', 'com_cs')->_join('comision_siguiente', 'com') . '
' . EntitySql::getInstanceFromString('division', 'com_cs_dvi')->_join('division', 'com_cs') . '
' . EntitySql::getInstanceFromString('plan', 'com_cs_dvi_pla')->_join('plan', 'com_cs_dvi') . '
' . EntitySql::getInstanceFromString('sede', 'com_cs_dvi_sed')->_join('sede', 'com_cs_dvi') . '
' . EntitySql::getInstanceFromString('domicilio', 'com_cs_dvi_sed_dom')->_join('domicilio', 'com_cs_dvi_sed') . '
' . EntitySql::getInstanceFromString('id_persona', 'com_cs_dvi_sed_coo')->_join('coordinador', 'com_cs_dvi_sed') . '
' . EntitySql::getInstanceFromString('alumno', 'com_cs_dvi_sed_coo_alumper')->_joinR('persona', 'com_cs_dvi_sed_coo') . '
' . EntitySql::getInstanceFromString('division', 'com_dvi')->_join('division', 'com') . '
' . EntitySql::getInstanceFromString('plan', 'com_dvi_pla')->_join('plan', 'com_dvi') . '
' . EntitySql::getInstanceFromString('sede', 'com_dvi_sed')->_join('sede', 'com_dvi') . '
' . EntitySql::getInstanceFromString('domicilio', 'com_dvi_sed_dom')->_join('domicilio', 'com_dvi_sed') . '
' . EntitySql::getInstanceFromString('id_persona', 'com_dvi_sed_coo')->_join('coordinador', 'com_dvi_sed') . '
' . EntitySql::getInstanceFromString('alumno', 'com_dvi_sed_coo_alumper')->_joinR('persona', 'com_dvi_sed_coo') . '
' . EntitySql::getInstanceFromString('carga_horaria', 'ch')->_join('carga_horaria', 'curs') . '
' . EntitySql::getInstanceFromString('asignatura', 'ch_asi')->_join('asignatura', 'ch') . '
' . EntitySql::getInstanceFromString('plan', 'ch_pla')->_join('plan', 'ch') . '
' . EntitySql::getInstanceFromString('id_persona', 'pa')->_join('profesor_activo', 'curs') . '
' . EntitySql::getInstanceFromString('alumno', 'pa_alumper')->_joinR('persona', 'pa') . '
' ;      
  }
  public function joinAux(){
    $join = "";
    if($j = $this->_joinAux()) $join .= "{$j}
";
    if ($j = EntitySql::getInstanceFromString('comision', 'com')->_joinAux()) $join .= "{$j}
";
    if ($j = EntitySql::getInstanceFromString('comision', 'com_cs')->_joinAux()) $join .= "{$j}
";
    if ($j = EntitySql::getInstanceFromString('division', 'com_cs_dvi')->_joinAux()) $join .= "{$j}
";
    if ($j = EntitySql::getInstanceFromString('plan', 'com_cs_dvi_pla')->_joinAux()) $join .= "{$j}
";
    if ($j = EntitySql::getInstanceFromString('sede', 'com_cs_dvi_sed')->_joinAux()) $join .= "{$j}
";
    if ($j = EntitySql::getInstanceFromString('domicilio', 'com_cs_dvi_sed_dom')->_joinAux()) $join .= "{$j}
";
    if ($j = EntitySql::getInstanceFromString('id_persona', 'com_cs_dvi_sed_coo')->_joinAux()) $join .= "{$j}
";
    if ($j = EntitySql::getInstanceFromString('alumno', 'com_cs_dvi_sed_coo_alumper')->_joinAux()) $join .= "{$j}
";
    if ($j = EntitySql::getInstanceFromString('division', 'com_dvi')->_joinAux()) $join .= "{$j}
";
    if ($j = EntitySql::getInstanceFromString('plan', 'com_dvi_pla')->_joinAux()) $join .= "{$j}
";
    if ($j = EntitySql::getInstanceFromString('sede', 'com_dvi_sed')->_joinAux()) $join .= "{$j}
";
    if ($j = EntitySql::getInstanceFromString('domicilio', 'com_dvi_sed_dom')->_joinAux()) $join .= "{$j}
";
    if ($j = EntitySql::getInstanceFromString('id_persona', 'com_dvi_sed_coo')->_joinAux()) $join .= "{$j}
";
    if ($j = EntitySql::getInstanceFromString('alumno', 'com_dvi_sed_coo_alumper')->_joinAux()) $join .= "{$j}
";
    if ($j = EntitySql::getInstanceFromString('carga_horaria', 'ch')->_joinAux()) $join .= "{$j}
";
    if ($j = EntitySql::getInstanceFromString('asignatura', 'ch_asi')->_joinAux()) $join .= "{$j}
";
    if ($j = EntitySql::getInstanceFromString('plan', 'ch_pla')->_joinAux()) $join .= "{$j}
";
    if ($j = EntitySql::getInstanceFromString('id_persona', 'pa')->_joinAux()) $join .= "{$j}
";
    if ($j = EntitySql::getInstanceFromString('alumno', 'pa_alumper')->_joinAux()) $join .= "{$j}
";
  return $join;
  }


  //***** @override *****
  public function conditionSearch($search = ""){
    if(empty($search)) return '';
    $condition = $this->_conditionSearch($search) . "
 OR " . EntitySql::getInstanceFromString('comision', 'com')->_conditionSearch($search) . "
 OR " . EntitySql::getInstanceFromString('division', 'com_dvi')->_conditionSearch($search) . "
 OR " . EntitySql::getInstanceFromString('plan', 'com_dvi_pla')->_conditionSearch($search) . "
 OR " . EntitySql::getInstanceFromString('sede', 'com_dvi_sed')->_conditionSearch($search) . "
 OR " . EntitySql::getInstanceFromString('domicilio', 'com_dvi_sed_dom')->_conditionSearch($search) . "
 OR " . EntitySql::getInstanceFromString('id_persona', 'com_dvi_sed_coo')->_conditionSearch($search) . "
 OR " . EntitySql::getInstanceFromString('alumno', 'com_dvi_sed_coo_alumper')->_conditionSearch($search) . "
 OR " . EntitySql::getInstanceFromString('carga_horaria', 'ch')->_conditionSearch($search) . "
 OR " . EntitySql::getInstanceFromString('asignatura', 'ch_asi')->_conditionSearch($search) . "
 OR " . EntitySql::getInstanceFromString('plan', 'ch_pla')->_conditionSearch($search) . "
 OR " . EntitySql::getInstanceFromString('id_persona', 'pa')->_conditionSearch($search) . "
 OR " . EntitySql::getInstanceFromString('alumno', 'pa_alumper')->_conditionSearch($search);
    return "(" . $condition . ")";
  }


  //***** @override *****
  public function _conditionSearch($search = ""){
    if(empty($search)) return '';
    $p = $this->prf();
    $condition = "";

    $field = $this->_mappingField($p.'id');
    $condition .= "" . $this->format->_conditionNumberApprox($field, $search);
    $field = $this->_mappingField($p.'estado');
    $condition .= " OR " . $this->format->_conditionTextApprox($field, $search);
    $field = $this->_mappingField($p.'alta');
    $condition .= " OR " . $this->format->_conditionTimestampApprox($field, $search);
    $field = $this->_mappingField($p.'baja');
    $condition .= " OR " . $this->format->_conditionTimestampApprox($field, $search);
    $field = $this->_mappingField($p.'horario');
    $condition .= " OR " . $this->format->_conditionTextApprox($field, $search);
    return "(" . $condition . ")";
  }

  //@override
  public function _conditionAdvanced($field, $option, $value){
    $p = $this->prf();

    $f = $this->_mappingField($field);
    switch ($field){
      case "{$p}id": return $this->format->conditionNumber($f, $value, $option);
      case "{$p}estado": return $this->format->conditionText($f, $value, $option);
      case "{$p}horario": return $this->format->conditionText($f, $value, $option);
      case "{$p}comision": return $this->format->conditionNumber($f, $value, $option);
      case "{$p}carga_horaria": return $this->format->conditionNumber($f, $value, $option);
      case "{$p}profesor_activo": return $this->format->conditionNumber($f, $value, $option);
    }
  }

  //@override
  protected function conditionAdvancedMain($field, $option, $value) {
    if($c = $this->_conditionAdvanced($field, $option, $value)) return $c;
    if($c = EntitySql::getInstanceFromString('comision','com')->_conditionAdvanced($field, $option, $value)) return $c;
    if($c = EntitySql::getInstanceFromString('comision','com_cs')->_conditionAdvanced($field, $option, $value)) return $c;
    if($c = EntitySql::getInstanceFromString('division','com_cs_dvi')->_conditionAdvanced($field, $option, $value)) return $c;
    if($c = EntitySql::getInstanceFromString('plan','com_cs_dvi_pla')->_conditionAdvanced($field, $option, $value)) return $c;
    if($c = EntitySql::getInstanceFromString('sede','com_cs_dvi_sed')->_conditionAdvanced($field, $option, $value)) return $c;
    if($c = EntitySql::getInstanceFromString('domicilio','com_cs_dvi_sed_dom')->_conditionAdvanced($field, $option, $value)) return $c;
    if($c = EntitySql::getInstanceFromString('id_persona','com_cs_dvi_sed_coo')->_conditionAdvanced($field, $option, $value)) return $c;
    if($c = EntitySql::getInstanceFromString('alumno','com_cs_dvi_sed_coo_alumper')->_conditionAdvanced($field, $option, $value)) return $c;
    if($c = EntitySql::getInstanceFromString('division','com_dvi')->_conditionAdvanced($field, $option, $value)) return $c;
    if($c = EntitySql::getInstanceFromString('plan','com_dvi_pla')->_conditionAdvanced($field, $option, $value)) return $c;
    if($c = EntitySql::getInstanceFromString('sede','com_dvi_sed')->_conditionAdvanced($field, $option, $value)) return $c;
    if($c = EntitySql::getInstanceFromString('domicilio','com_dvi_sed_dom')->_conditionAdvanced($field, $option, $value)) return $c;
    if($c = EntitySql::getInstanceFromString('id_persona','com_dvi_sed_coo')->_conditionAdvanced($field, $option, $value)) return $c;
    if($c = EntitySql::getInstanceFromString('alumno','com_dvi_sed_coo_alumper')->_conditionAdvanced($field, $option, $value)) return $c;
    if($c = EntitySql::getInstanceFromString('carga_horaria','ch')->_conditionAdvanced($field, $option, $value)) return $c;
    if($c = EntitySql::getInstanceFromString('asignatura','ch_asi')->_conditionAdvanced($field, $option, $value)) return $c;
    if($c = EntitySql::getInstanceFromString('plan','ch_pla')->_conditionAdvanced($field, $option, $value)) return $c;
    if($c = EntitySql::getInstanceFromString('id_persona','pa')->_conditionAdvanced($field, $option, $value)) return $c;
    if($c = EntitySql::getInstanceFromString('alumno','pa_alumper')->_conditionAdvanced($field, $option, $value)) return $c;
    throw new Exception("No pudo definirse la condicion avanzada {$field} {$option} {$value}");
  }

  //@override
  public function conditionAux() {
    $sqlCond = $this->_conditionAux();
    if($cond = EntitySql::getInstanceFromString('comision', 'com')->_conditionAux()) $sqlCond .= concat($cond, ' AND', '', $sqlCond);
    if($cond = EntitySql::getInstanceFromString('comision', 'com_cs')->_conditionAux()) $sqlCond .= concat($cond, ' AND', '', $sqlCond);
    if($cond = EntitySql::getInstanceFromString('division', 'com_cs_dvi')->_conditionAux()) $sqlCond .= concat($cond, ' AND', '', $sqlCond);
    if($cond = EntitySql::getInstanceFromString('plan', 'com_cs_dvi_pla')->_conditionAux()) $sqlCond .= concat($cond, ' AND', '', $sqlCond);
    if($cond = EntitySql::getInstanceFromString('sede', 'com_cs_dvi_sed')->_conditionAux()) $sqlCond .= concat($cond, ' AND', '', $sqlCond);
    if($cond = EntitySql::getInstanceFromString('domicilio', 'com_cs_dvi_sed_dom')->_conditionAux()) $sqlCond .= concat($cond, ' AND', '', $sqlCond);
    if($cond = EntitySql::getInstanceFromString('id_persona', 'com_cs_dvi_sed_coo')->_conditionAux()) $sqlCond .= concat($cond, ' AND', '', $sqlCond);
    if($cond = EntitySql::getInstanceFromString('alumno', 'com_cs_dvi_sed_coo_alumper')->_conditionAux()) $sqlCond .= concat($cond, ' AND', '', $sqlCond);
    if($cond = EntitySql::getInstanceFromString('division', 'com_dvi')->_conditionAux()) $sqlCond .= concat($cond, ' AND', '', $sqlCond);
    if($cond = EntitySql::getInstanceFromString('plan', 'com_dvi_pla')->_conditionAux()) $sqlCond .= concat($cond, ' AND', '', $sqlCond);
    if($cond = EntitySql::getInstanceFromString('sede', 'com_dvi_sed')->_conditionAux()) $sqlCond .= concat($cond, ' AND', '', $sqlCond);
    if($cond = EntitySql::getInstanceFromString('domicilio', 'com_dvi_sed_dom')->_conditionAux()) $sqlCond .= concat($cond, ' AND', '', $sqlCond);
    if($cond = EntitySql::getInstanceFromString('id_persona', 'com_dvi_sed_coo')->_conditionAux()) $sqlCond .= concat($cond, ' AND', '', $sqlCond);
    if($cond = EntitySql::getInstanceFromString('alumno', 'com_dvi_sed_coo_alumper')->_conditionAux()) $sqlCond .= concat($cond, ' AND', '', $sqlCond);
    if($cond = EntitySql::getInstanceFromString('carga_horaria', 'ch')->_conditionAux()) $sqlCond .= concat($cond, ' AND', '', $sqlCond);
    if($cond = EntitySql::getInstanceFromString('asignatura', 'ch_asi')->_conditionAux()) $sqlCond .= concat($cond, ' AND', '', $sqlCond);
    if($cond = EntitySql::getInstanceFromString('plan', 'ch_pla')->_conditionAux()) $sqlCond .= concat($cond, ' AND', '', $sqlCond);
    if($cond = EntitySql::getInstanceFromString('id_persona', 'pa')->_conditionAux()) $sqlCond .= concat($cond, ' AND', '', $sqlCond);
    if($cond = EntitySql::getInstanceFromString('alumno', 'pa_alumper')->_conditionAux()) $sqlCond .= concat($cond, ' AND', '', $sqlCond);
    return (empty($sqlCond)) ? '' : "({$sqlCond})";
  }


}
