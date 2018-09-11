<?php

require_once("class/model/sql/curso/Main.php");

class CursoSql extends CursoSqlMain {


  public function _mappingField($field, $prefix='') {
    $field_ = parent::_mappingField($field, $prefix);

    $prf = (empty($prefix)) ? '' : $prefix . '_';
    $aux = (empty($prefix)) ? 'horario' : $prf."horario";
    $aux2 = (empty($prefix)) ? 'pa' : $prf . "pa";


    switch ($field) {
      case $prf.'horario': return $aux.".horario";
      case $prf.'profesor_activo': return $aux2.".id";

      default: return $field_;
    }
  }



  //JOIN HORARIO: todos los horarios asociados al curso pueden ser concatenados en uno solo (ver fieldHorario)
  public function _joinAux($prefix = ""){
    $p = (empty($prefix)) ? '' : $prefix . '_';
    $t = (empty($prefix)) ? 'curs.' : $prefix . '.';

    return "
LEFT JOIN (
  SELECT curso.id AS curso, GROUP_CONCAT(dia.dia, \" \", TIME_FORMAT(horario.hora_inicio, '%H:%i'), \" a \", TIME_FORMAT(horario.hora_fin, '%H:%i')) AS horario
  FROM curso
  INNER JOIN horario ON (horario.curso = curso.id)
  INNER JOIN dia ON (dia.id = horario.dia)
  INNER JOIN comision ON (comision.id = curso.comision)
  INNER JOIN division ON ( comision.division = division.id )
  INNER JOIN plan ON ( division.plan = plan.id )
  AND comision.autorizada
  AND comision.publicar
  AND plan.id !=3
  GROUP BY curso.id
) AS {$p}horario ON ({$p}horario.curso = {$t}id)
";
  }

}
