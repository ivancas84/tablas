<?php



class ClassSql__conditionSearch extends GenerateEntity{

  protected $or = false; //boolean. Flag para indicar si existe condicion de busqueda


  public function defineOr() {
    if(!$this->or){
      $this->or = true;
      return false;
    }
    return true;
  }




  protected function start(){
    $this->string .= "  public function _conditionSearch(\$search = \"\"){
    if(empty(\$search)) return '';
    \$p = \$this->prf();
    \$condition = \"\";

";
  }


  public function generate(){
    $this->start();
    $this->condition($this->getEntity(), $this->getEntity()->getAlias());
    $this->end();
    return $this->string;
  }




  protected function end(){
    $this->string .= "    return \"(\" . \$condition . \")\";
  }

";
  }

  protected function text($fieldName, $alias){
    $or = ($this->defineOr()) ? " OR " : "";

    $this->string .= "    \$field = \$this->_mappingField(\$p.'{$fieldName}');
    \$condition .= \"". $or . "\" . \$this->format->conditionText(\$field, \$search, '=~');
" ;

  }

  protected function number($fieldName, $alias){
    $or = ($this->defineOr()) ? " OR " : "";

    $this->string .= "    \$field = \$this->_mappingField(\$p.'{$fieldName}');
    \$condition .= \"". $or . "\" . \$this->format->_conditionNumber(\$field, \$search, \"=~\");
" ;
  }

  protected function date($fieldName, $alias){
    $or = ($this->defineOr()) ? " OR " : "";

    $this->string .= "    \$field = \$this->_mappingField(\$p.'{$fieldName}');
    \$condition .= \"". $or . "\" . \$this->format->_conditionDate(\$field, \$search, \"=~\");
" ;
  }

  protected function year($fieldName, $alias){
    $or = ($this->defineOr()) ? " OR " : "";

    $this->string .= "    \$field = \$this->_mappingField(\$p.'{$fieldName}');
    \$condition .= \"". $or . "\" . \$this->format->_conditionYear(\$field, \$search, \"=~\");
" ;
  }

  protected function timestamp ($fieldName, $alias){
    $or = ($this->defineOr()) ? " OR " : "";


    $this->string .= "    \$field = \$this->_mappingField(\$p.'{$fieldName}');
    \$condition .= \"". $or . "\" . \$this->format->_conditionTimestamp(\$field, \$search, \"=~\");
" ;
  }

  protected function condition(Entity $entity, $alias){
    $fields = $entity->getFieldsByType(["pk", "nf"]);

    foreach ($fields as $field) {
      if($field->isHidden()) continue;
      switch ($field->getDataType()) {
        case "string": case "text": $this->text($field->getName(), $alias); break;
        case "integer": case "float": $this->number($field->getName(), $alias); break;
        case "date": $this->date($field->getName(), $alias); break;
        case "year": $this->year($field->getName(), $alias); break;
        case "timestamp": $this->timestamp($field->getName(), $alias); break;
      }
    }
  }







}
