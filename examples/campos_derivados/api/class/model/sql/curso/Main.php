<?php
require_once("class/model/Sql.php");

class CursoSqlMain extends EntitySql{

  public function __construct(){
    $this->entity = new CursoEntity;
    $this->db = Dba::dbInstance();
  }

  //@override
  public function _mappingField($field, $prefix=''){
    $prf = (empty($prefix)) ? '' : $prefix . '_';
    $prt = (empty($prefix)) ? 'curs' : $prefix;

    switch ($field) {
      case $prf.'id': return $prt.".id";
      case $prf.'comision': return $prt.".comision";
      case $prf.'carga_horaria': return $prt.".carga_horaria";
      case $prf.'horario': return $prt.".horario";
      case $prf.'comision': return $prt.".comision";
      case $prf.'carga_horaria': return $prt.".carga_horaria";
      case $prf.'profesor_activo': return $prt.".profesor_activo";
      default: return null;
    }
  }

  //@override
  public function mappingField($field){
    $field_ = $this->_mappingField($field); if($field_) return $field_;
    $field_ = Dba::sql('comision')->_mappingField($field, 'com'); if($field_) return $field_;
    $field_ = Dba::sql('division')->_mappingField($field, 'com_dvi'); if($field_) return $field_;
    $field_ = Dba::sql('plan')->_mappingField($field, 'com_dvi_pla'); if($field_) return $field_;
    $field_ = Dba::sql('sede')->_mappingField($field, 'com_dvi_sed'); if($field_) return $field_;
    $field_ = Dba::sql('domicilio')->_mappingField($field, 'com_dvi_sed_dom'); if($field_) return $field_;
    $field_ = Dba::sql('id_persona')->_mappingField($field, 'com_dvi_sed_coo'); if($field_) return $field_;
    $field_ = Dba::sql('alumno')->_mappingField($field, 'com_dvi_sed_coo_alumper'); if($field_) return $field_;
    $field_ = Dba::sql('carga_horaria')->_mappingField($field, 'ch'); if($field_) return $field_;
    $field_ = Dba::sql('asignatura')->_mappingField($field, 'ch_asi'); if($field_) return $field_;
    $field_ = Dba::sql('plan')->_mappingField($field, 'ch_pla'); if($field_) return $field_;
    $field_ = Dba::sql('id_persona')->_mappingField($field, 'pa'); if($field_) return $field_;
    $field_ = Dba::sql('alumno')->_mappingField($field, 'pa_alumper'); if($field_) return $field_;
    throw new Exception("Campo no reconocido " . $field);
  }

  public function fields($prefix = ''){
    $p = (empty($prefix)) ?  ''  : $prefix . '_';
    return '
' . $this->_mappingField($p.'id', $prefix) . ' AS ' . $p.'id,
' . $this->_mappingField($p.'comision', $prefix) . ' AS ' . $p.'comision,
' . $this->_mappingField($p.'carga_horaria', $prefix) . ' AS ' . $p.'carga_horaria,
' . $this->_mappingField($p.'horario', $prefix) . ' AS ' . $p.'horario,
' . $this->_mappingField($p.'comision', $prefix) . ' AS ' . $p.'comision,
' . $this->_mappingField($p.'carga_horaria', $prefix) . ' AS ' . $p.'carga_horaria,
' . $this->_mappingField($p.'profesor_activo', $prefix) . ' AS ' . $p.'profesor_activo';
  }

    public function fieldsFull(){
    $fields = $this->fields() . ', ';
    $fields .= Dba::sql('comision')->fields('com') . ',
';
    $fields .= Dba::sql('comision')->fields('com_cs') . ',
';
    $fields .= Dba::sql('division')->fields('com_cs_dvi') . ',
';
    $fields .= Dba::sql('plan')->fields('com_cs_dvi_pla') . ',
';
    $fields .= Dba::sql('sede')->fields('com_cs_dvi_sed') . ',
';
    $fields .= Dba::sql('domicilio')->fields('com_cs_dvi_sed_dom') . ',
';
    $fields .= Dba::sql('id_persona')->fields('com_cs_dvi_sed_coo') . ',
';
    $fields .= Dba::sql('alumno')->fields('com_cs_dvi_sed_coo_alumper') . ',
';
    $fields .= Dba::sql('division')->fields('com_dvi') . ',
';
    $fields .= Dba::sql('plan')->fields('com_dvi_pla') . ',
';
    $fields .= Dba::sql('sede')->fields('com_dvi_sed') . ',
';
    $fields .= Dba::sql('domicilio')->fields('com_dvi_sed_dom') . ',
';
    $fields .= Dba::sql('id_persona')->fields('com_dvi_sed_coo') . ',
';
    $fields .= Dba::sql('alumno')->fields('com_dvi_sed_coo_alumper') . ',
';
    $fields .= Dba::sql('carga_horaria')->fields('ch') . ',
';
    $fields .= Dba::sql('asignatura')->fields('ch_asi') . ',
';
    $fields .= Dba::sql('plan')->fields('ch_pla') . ',
';
    $fields .= Dba::sql('id_persona')->fields('pa') . ',
';
    $fields .= Dba::sql('alumno')->fields('pa_alumper') . '';
    return $fields;
  }

  public function fieldsLabelFull(){
    $fields = '';
    $fields .= Dba::sql('comision')->_fieldsLabel('com') . ',
';
    $fields .= Dba::sql('comision')->_fieldsLabel('com_cs') . ',
';
    $fields .= Dba::sql('division')->_fieldsLabel('com_cs_dvi') . ',
';
    $fields .= Dba::sql('plan')->_fieldsLabel('com_cs_dvi_pla') . ',
';
    $fields .= Dba::sql('sede')->_fieldsLabel('com_cs_dvi_sed') . ',
';
    $fields .= Dba::sql('domicilio')->_fieldsLabel('com_cs_dvi_sed_dom') . ',
';
    $fields .= Dba::sql('id_persona')->_fieldsLabel('com_cs_dvi_sed_coo') . ',
';
    $fields .= Dba::sql('alumno')->_fieldsLabel('com_cs_dvi_sed_coo_alumper') . ',
';
    $fields .= Dba::sql('division')->_fieldsLabel('com_dvi') . ',
';
    $fields .= Dba::sql('plan')->_fieldsLabel('com_dvi_pla') . ',
';
    $fields .= Dba::sql('sede')->_fieldsLabel('com_dvi_sed') . ',
';
    $fields .= Dba::sql('domicilio')->_fieldsLabel('com_dvi_sed_dom') . ',
';
    $fields .= Dba::sql('id_persona')->_fieldsLabel('com_dvi_sed_coo') . ',
';
    $fields .= Dba::sql('alumno')->_fieldsLabel('com_dvi_sed_coo_alumper') . ',
';
    $fields .= Dba::sql('carga_horaria')->_fieldsLabel('ch') . ',
';
    $fields .= Dba::sql('asignatura')->_fieldsLabel('ch_asi') . ',
';
    $fields .= Dba::sql('plan')->_fieldsLabel('ch_pla') . ',
';
    $fields .= Dba::sql('id_persona')->_fieldsLabel('pa') . ',
';
    $fields .= Dba::sql('alumno')->_fieldsLabel('pa_alumper') . '';
    return $fields;
  }

  public function fieldsAux(){
    $fields = $this->_fieldsAux();

    $fields_ = Dba::sql('comision')->_fieldsAux('com');
    $fields .= concat($fields_, ', ', '', $fields);

    $fields_ = Dba::sql('comision')->_fieldsAux('com_cs');
    $fields .= concat($fields_, ', ', '', $fields);

    $fields_ = Dba::sql('division')->_fieldsAux('com_cs_dvi');
    $fields .= concat($fields_, ', ', '', $fields);

    $fields_ = Dba::sql('plan')->_fieldsAux('com_cs_dvi_pla');
    $fields .= concat($fields_, ', ', '', $fields);

    $fields_ = Dba::sql('sede')->_fieldsAux('com_cs_dvi_sed');
    $fields .= concat($fields_, ', ', '', $fields);

    $fields_ = Dba::sql('domicilio')->_fieldsAux('com_cs_dvi_sed_dom');
    $fields .= concat($fields_, ', ', '', $fields);

    $fields_ = Dba::sql('id_persona')->_fieldsAux('com_cs_dvi_sed_coo');
    $fields .= concat($fields_, ', ', '', $fields);

    $fields_ = Dba::sql('alumno')->_fieldsAux('com_cs_dvi_sed_coo_alumper');
    $fields .= concat($fields_, ', ', '', $fields);

    $fields_ = Dba::sql('division')->_fieldsAux('com_dvi');
    $fields .= concat($fields_, ', ', '', $fields);

    $fields_ = Dba::sql('plan')->_fieldsAux('com_dvi_pla');
    $fields .= concat($fields_, ', ', '', $fields);

    $fields_ = Dba::sql('sede')->_fieldsAux('com_dvi_sed');
    $fields .= concat($fields_, ', ', '', $fields);

    $fields_ = Dba::sql('domicilio')->_fieldsAux('com_dvi_sed_dom');
    $fields .= concat($fields_, ', ', '', $fields);

    $fields_ = Dba::sql('id_persona')->_fieldsAux('com_dvi_sed_coo');
    $fields .= concat($fields_, ', ', '', $fields);

    $fields_ = Dba::sql('alumno')->_fieldsAux('com_dvi_sed_coo_alumper');
    $fields .= concat($fields_, ', ', '', $fields);

    $fields_ = Dba::sql('carga_horaria')->_fieldsAux('ch');
    $fields .= concat($fields_, ', ', '', $fields);

    $fields_ = Dba::sql('asignatura')->_fieldsAux('ch_asi');
    $fields .= concat($fields_, ', ', '', $fields);

    $fields_ = Dba::sql('plan')->_fieldsAux('ch_pla');
    $fields .= concat($fields_, ', ', '', $fields);

    $fields_ = Dba::sql('id_persona')->_fieldsAux('pa');
    $fields .= concat($fields_, ', ', '', $fields);

    $fields_ = Dba::sql('alumno')->_fieldsAux('pa_alumper');
    $fields .= concat($fields_, ', ', '', $fields);

    return $fields;
  }


  //@override
  public function join(){
    $sql = '';
    $sql .= Dba::sql('comision')->_join('comision', 'curs', 'com');
    $sql .= Dba::sql('comision')->_join('comision_siguiente', 'com', 'com_cs');
    $sql .= Dba::sql('division')->_join('division', 'com_cs', 'com_cs_dvi');
    $sql .= Dba::sql('plan')->_join('plan', 'com_cs_dvi', 'com_cs_dvi_pla');
    $sql .= Dba::sql('sede')->_join('sede', 'com_cs_dvi', 'com_cs_dvi_sed');
    $sql .= Dba::sql('domicilio')->_join('domicilio', 'com_cs_dvi_sed', 'com_cs_dvi_sed_dom');
    $sql .= Dba::sql('id_persona')->_join('coordinador', 'com_cs_dvi_sed', 'com_cs_dvi_sed_coo');
    $sql .= Dba::sql('alumno')->_joinR('persona', 'com_cs_dvi_sed_coo', 'com_cs_dvi_sed_coo_alumper');
    $sql .= Dba::sql('division')->_join('division', 'com', 'com_dvi');
    $sql .= Dba::sql('plan')->_join('plan', 'com_dvi', 'com_dvi_pla');
    $sql .= Dba::sql('sede')->_join('sede', 'com_dvi', 'com_dvi_sed');
    $sql .= Dba::sql('domicilio')->_join('domicilio', 'com_dvi_sed', 'com_dvi_sed_dom');
    $sql .= Dba::sql('id_persona')->_join('coordinador', 'com_dvi_sed', 'com_dvi_sed_coo');
    $sql .= Dba::sql('alumno')->_joinR('persona', 'com_dvi_sed_coo', 'com_dvi_sed_coo_alumper');
    $sql .= Dba::sql('carga_horaria')->_join('carga_horaria', 'curs', 'ch');
    $sql .= Dba::sql('asignatura')->_join('asignatura', 'ch', 'ch_asi');
    $sql .= Dba::sql('plan')->_join('plan', 'ch', 'ch_pla');
    $sql .= Dba::sql('id_persona')->_join('profesor_activo', 'curs', 'pa');
    $sql .= Dba::sql('alumno')->_joinR('persona', 'pa', 'pa_alumper');
    return $sql;
  }
public function joinAux(){
    $join = $this->_joinAux() . '
';
    $sql = new ComisionSql; $join .= $sql->_joinAux('com') . '
';
    $sql = new ComisionSql; $join .= $sql->_joinAux('com_cs') . '
';
    $sql = new DivisionSql; $join .= $sql->_joinAux('com_cs_dvi') . '
';
    $sql = new PlanSql; $join .= $sql->_joinAux('com_cs_dvi_pla') . '
';
    $sql = new SedeSql; $join .= $sql->_joinAux('com_cs_dvi_sed') . '
';
    $sql = new DomicilioSql; $join .= $sql->_joinAux('com_cs_dvi_sed_dom') . '
';
    $sql = new IdPersonaSql; $join .= $sql->_joinAux('com_cs_dvi_sed_coo') . '
';
    $sql = new AlumnoSql; $join .= $sql->_joinAux('com_cs_dvi_sed_coo_alumper') . '
';
    $sql = new DivisionSql; $join .= $sql->_joinAux('com_dvi') . '
';
    $sql = new PlanSql; $join .= $sql->_joinAux('com_dvi_pla') . '
';
    $sql = new SedeSql; $join .= $sql->_joinAux('com_dvi_sed') . '
';
    $sql = new DomicilioSql; $join .= $sql->_joinAux('com_dvi_sed_dom') . '
';
    $sql = new IdPersonaSql; $join .= $sql->_joinAux('com_dvi_sed_coo') . '
';
    $sql = new AlumnoSql; $join .= $sql->_joinAux('com_dvi_sed_coo_alumper') . '
';
    $sql = new CargaHorariaSql; $join .= $sql->_joinAux('ch') . '
';
    $sql = new AsignaturaSql; $join .= $sql->_joinAux('ch_asi') . '
';
    $sql = new PlanSql; $join .= $sql->_joinAux('ch_pla') . '
';
    $sql = new IdPersonaSql; $join .= $sql->_joinAux('pa') . '
';
    $sql = new AlumnoSql; $join .= $sql->_joinAux('pa_alumper') . '
';
    return $join;
  }


  //***** @override *****
  public function conditionSearch($search = ""){
    if(empty($search)) return '';
    $condition = $this->_conditionSearch($search);

  $condition .= " OR " . Dba::sql('comision')->_conditionSearch($search, 'com');
  $condition .= " OR " . Dba::sql('division')->_conditionSearch($search, 'com_dvi');
  $condition .= " OR " . Dba::sql('plan')->_conditionSearch($search, 'com_dvi_pla');
  $condition .= " OR " . Dba::sql('sede')->_conditionSearch($search, 'com_dvi_sed');
  $condition .= " OR " . Dba::sql('domicilio')->_conditionSearch($search, 'com_dvi_sed_dom');
  $condition .= " OR " . Dba::sql('id_persona')->_conditionSearch($search, 'com_dvi_sed_coo');
  $condition .= " OR " . Dba::sql('alumno')->_conditionSearch($search, 'com_dvi_sed_coo_alumper');
  $condition .= " OR " . Dba::sql('carga_horaria')->_conditionSearch($search, 'ch');
  $condition .= " OR " . Dba::sql('asignatura')->_conditionSearch($search, 'ch_asi');
  $condition .= " OR " . Dba::sql('plan')->_conditionSearch($search, 'ch_pla');
  $condition .= " OR " . Dba::sql('id_persona')->_conditionSearch($search, 'pa');
  $condition .= " OR " . Dba::sql('alumno')->_conditionSearch($search, 'pa_alumper');
    return "(" . $condition . ")";
  }


  //***** @override *****
  public function _conditionSearch($search = "", $prefix = ""){
    if(empty($search)) return '';
    $p = ($prefix) ? $prefix . '_' : '';
    $condition = "";

    $field = $this->_mappingField($p . 'id', $prefix);
    $condition .= "" . $this->_conditionNumberApprox($field, $search);
    $field = $this->_mappingField($p . 'comision', $prefix);
    $condition .= " OR " . $this->_conditionNumberApprox($field, $search);
    $field = $this->_mappingField($p . 'carga_horaria', $prefix);
    $condition .= " OR " . $this->_conditionNumberApprox($field, $search);
    $field = $this->_mappingField($p . 'horario', $prefix);
    $condition .= " OR " . $this->_conditionTextApprox($field, $search);
    return "(" . $condition . ")";
  }

  //@override
  public function _conditionAdvanced($field, $option, $value, $prefix = ''){
    $p = (empty($prefix)) ?  ''  : $prefix . '_';

    $f = $this->_mappingField($field, $prefix);
    switch ($field){
      case "{$p}id": return $this->conditionNumber($f, $value, $option);
      case "{$p}comision": return $this->conditionNumber($f, $value, $option);
      case "{$p}carga_horaria": return $this->conditionNumber($f, $value, $option);
       case "{$p}horario": return $this->conditionText($f, $value, $option);
      case "{$p}comision": return $this->conditionNumber($f, $value, $option);
      case "{$p}carga_horaria": return $this->conditionNumber($f, $value, $option);
      case "{$p}profesor_activo": return $this->conditionNumber($f, $value, $option);
    }
  }

  //@override
  protected function conditionAdvancedMain($field, $option, $value, $prefix = '') {
    $p = (empty($prefix)) ?  ''  : $prefix . '_';

    if($c = $this->_conditionAdvanced($field, $option, $value, $prefix)) return $c;
    $sql = new ComisionSql; if($c = $sql->_conditionAdvanced($field, $option, $value, $p.'com')) return $c;
    $sql = new ComisionSql; if($c = $sql->_conditionAdvanced($field, $option, $value, $p.'com_cs')) return $c;
    $sql = new DivisionSql; if($c = $sql->_conditionAdvanced($field, $option, $value, $p.'com_cs_dvi')) return $c;
    $sql = new PlanSql; if($c = $sql->_conditionAdvanced($field, $option, $value, $p.'com_cs_dvi_pla')) return $c;
    $sql = new SedeSql; if($c = $sql->_conditionAdvanced($field, $option, $value, $p.'com_cs_dvi_sed')) return $c;
    $sql = new DomicilioSql; if($c = $sql->_conditionAdvanced($field, $option, $value, $p.'com_cs_dvi_sed_dom')) return $c;
    $sql = new IdPersonaSql; if($c = $sql->_conditionAdvanced($field, $option, $value, $p.'com_cs_dvi_sed_coo')) return $c;
    $sql = new AlumnoSql; if($c = $sql->_conditionAdvanced($field, $option, $value, $p.'com_cs_dvi_sed_coo_alumper')) return $c;
    $sql = new DivisionSql; if($c = $sql->_conditionAdvanced($field, $option, $value, $p.'com_dvi')) return $c;
    $sql = new PlanSql; if($c = $sql->_conditionAdvanced($field, $option, $value, $p.'com_dvi_pla')) return $c;
    $sql = new SedeSql; if($c = $sql->_conditionAdvanced($field, $option, $value, $p.'com_dvi_sed')) return $c;
    $sql = new DomicilioSql; if($c = $sql->_conditionAdvanced($field, $option, $value, $p.'com_dvi_sed_dom')) return $c;
    $sql = new IdPersonaSql; if($c = $sql->_conditionAdvanced($field, $option, $value, $p.'com_dvi_sed_coo')) return $c;
    $sql = new AlumnoSql; if($c = $sql->_conditionAdvanced($field, $option, $value, $p.'com_dvi_sed_coo_alumper')) return $c;
    $sql = new CargaHorariaSql; if($c = $sql->_conditionAdvanced($field, $option, $value, $p.'ch')) return $c;
    $sql = new AsignaturaSql; if($c = $sql->_conditionAdvanced($field, $option, $value, $p.'ch_asi')) return $c;
    $sql = new PlanSql; if($c = $sql->_conditionAdvanced($field, $option, $value, $p.'ch_pla')) return $c;
    $sql = new IdPersonaSql; if($c = $sql->_conditionAdvanced($field, $option, $value, $p.'pa')) return $c;
    $sql = new AlumnoSql; if($c = $sql->_conditionAdvanced($field, $option, $value, $p.'pa_alumper')) return $c;
    throw new Exception("No pudo definirse la condicion avanzada {$field} {$option} {$value}");
  }

  //@override
  public function conditionAux() {
    $sqlCond = $this->_conditionAux();
    $sql = new ComisionSql; $cond = $sql->_conditionAux('com');
    $sqlCond .= concat($cond, ' AND', '', $sqlCond);

    $sql = new ComisionSql; $cond = $sql->_conditionAux('com_cs');
    $sqlCond .= concat($cond, ' AND', '', $sqlCond);

    $sql = new DivisionSql; $cond = $sql->_conditionAux('com_cs_dvi');
    $sqlCond .= concat($cond, ' AND', '', $sqlCond);

    $sql = new PlanSql; $cond = $sql->_conditionAux('com_cs_dvi_pla');
    $sqlCond .= concat($cond, ' AND', '', $sqlCond);

    $sql = new SedeSql; $cond = $sql->_conditionAux('com_cs_dvi_sed');
    $sqlCond .= concat($cond, ' AND', '', $sqlCond);

    $sql = new DomicilioSql; $cond = $sql->_conditionAux('com_cs_dvi_sed_dom');
    $sqlCond .= concat($cond, ' AND', '', $sqlCond);

    $sql = new IdPersonaSql; $cond = $sql->_conditionAux('com_cs_dvi_sed_coo');
    $sqlCond .= concat($cond, ' AND', '', $sqlCond);

    $sql = new AlumnoSql; $cond = $sql->_conditionAux('com_cs_dvi_sed_coo_alumper');
    $sqlCond .= concat($cond, ' AND', '', $sqlCond);

    $sql = new DivisionSql; $cond = $sql->_conditionAux('com_dvi');
    $sqlCond .= concat($cond, ' AND', '', $sqlCond);

    $sql = new PlanSql; $cond = $sql->_conditionAux('com_dvi_pla');
    $sqlCond .= concat($cond, ' AND', '', $sqlCond);

    $sql = new SedeSql; $cond = $sql->_conditionAux('com_dvi_sed');
    $sqlCond .= concat($cond, ' AND', '', $sqlCond);

    $sql = new DomicilioSql; $cond = $sql->_conditionAux('com_dvi_sed_dom');
    $sqlCond .= concat($cond, ' AND', '', $sqlCond);

    $sql = new IdPersonaSql; $cond = $sql->_conditionAux('com_dvi_sed_coo');
    $sqlCond .= concat($cond, ' AND', '', $sqlCond);

    $sql = new AlumnoSql; $cond = $sql->_conditionAux('com_dvi_sed_coo_alumper');
    $sqlCond .= concat($cond, ' AND', '', $sqlCond);

    $sql = new CargaHorariaSql; $cond = $sql->_conditionAux('ch');
    $sqlCond .= concat($cond, ' AND', '', $sqlCond);

    $sql = new AsignaturaSql; $cond = $sql->_conditionAux('ch_asi');
    $sqlCond .= concat($cond, ' AND', '', $sqlCond);

    $sql = new PlanSql; $cond = $sql->_conditionAux('ch_pla');
    $sqlCond .= concat($cond, ' AND', '', $sqlCond);

    $sql = new IdPersonaSql; $cond = $sql->_conditionAux('pa');
    $sqlCond .= concat($cond, ' AND', '', $sqlCond);

    $sql = new AlumnoSql; $cond = $sql->_conditionAux('pa_alumper');
    $sqlCond .= concat($cond, ' AND', '', $sqlCond);

    return (empty($sqlCond)) ? '' : "({$sqlCond})";
  }


}
