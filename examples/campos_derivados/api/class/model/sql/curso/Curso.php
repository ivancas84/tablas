<?php

require_once("class/model/sql/curso/Main.php");

class CursoSql extends CursoSqlMain {

  public function _mappingField($field) {
    $field_ = parent::_mappingField($field);

    $prf = $this->prf();

    switch ($field) {
      case $prf.'horario': return $prf."horario.horario";
      case $prf.'profesor_activo': return $prf . "pa.id";

      default: return $field_;
    }
  }

  public function _joinAux(){
    $p = $this->prf();
    $t = $this->prt();

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
) AS {$p}horario ON ({$p}horario.curso = {$t}.id)
";
  }

}
