<?php

require_once("class/model/sql/idPersona/Main.php");

class IdPersonaSql extends IdPersonaSqlMain {
  public function _join($field, $from, $to = null) {
    $to = (empty($to)) ?  "idp"  : $to;

    switch($field){
    
      case "profesor_activo":
        return "
LEFT OUTER JOIN (
  SELECT
    curso.id AS curso, id_persona.id AS persona
  FROM curso
  INNER JOIN comision ON (curso.comision = comision.id)
  INNER JOIN toma ON (curso.id = toma.curso)
  INNER JOIN id_persona ON (toma.profesor = id_persona.id)
  WHERE (toma.estado = 'Aprobada' OR toma.estado = 'Pendiente')
  AND toma.estado_contralor != 'Modificar'
) AS {$to}_ ON ({$to}_.curso = {$from}.id)
LEFT OUTER JOIN " . $this->entity->getSn_() . " AS {$to} ON ({$to}.id = {$to}_.persona)
";

      default:
        return parent::_join($field, $from, $to);
    }


  }

}
