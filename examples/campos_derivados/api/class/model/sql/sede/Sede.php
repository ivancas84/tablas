<?php

require_once("class/model/sql/sede/Main.php");

class SedeSql extends SedeSqlMain {

  public function _mappingField($field) {
    $field_ = parent::_mappingField($field);

    $p = $this->prf();
    $aux = (empty($p)) ? 'coo' : $p . "coo";

    switch ($field) {
      case $p.'coordinador': return $aux.".id";
      default: return $field_;
    }
  }

}
