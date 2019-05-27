<?php

require_once("class/model/sql/idPersona/Main.php");

class IdPersonaSql extends IdPersonaSqlMain {

  public function _mappingField($field) {
    $field_ = parent::_mappingField($field);

    $p = $this->prf();
    $aux = (empty($p)) ? 'telefonos' : $p."telefonos";

    switch ($field) {
      case $p.'telefonos': return $aux.".telefonos";

      default: return $field_;
    }
  }


  public function _join($field, $from) {
    $t = $this->prt();

    switch($field){
      case "coordinador":
        return "LEFT OUTER JOIN (
  SELECT sede, persona
  FROM coordinador
  WHERE baja IS NULL
) AS {$t}_ ON ({$t}_.sede = {$from}.id)
LEFT OUTER JOIN {$this->entity->sn_()} AS {$t} ON ({$t}.id = {$t}_.persona)
";
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
  LIMIT 1
) AS {$t}_ ON ({$t}_.curso = {$from}.id)
LEFT OUTER JOIN {$this->entity->sn_()} AS {$t} ON ({$t}.id = {$t}_.persona)
";

      default:
        return parent::_join($field, $from);
    }
  }

  public function _joinAux(){
    $p = $this->prf();
    $t = $this->prt();

    return "
LEFT JOIN (
    SELECT id_persona.id AS persona, GROUP_CONCAT(telefono.prefijo, \" \", telefono.numero, \" (\", telefono.tipo, \")\") AS telefonos
    FROM id_persona
    INNER JOIN telefono ON (telefono.persona = id_persona.id)
    WHERE telefono.baja IS NULL
    GROUP BY persona
) AS {$p}telefonos ON ({$p}telefonos.persona = {$t}.id)
    ";
  }
}
