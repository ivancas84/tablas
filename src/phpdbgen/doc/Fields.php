<?php


require_once("GenerateEntityRecursiveFk.php");

class Doc_fields extends GenerateEntity{



  public function generate(){
    foreach($this->getEntity()->getFields() as $field) {
      $this->string .= $field->getName() . " ({$field->getAlias()}): {$field->getDataType()} {$field->getFieldType()}.";
      if($field->isNotNull()) $this->string .= " NOT NULL."; 
      if($field->isMain()) $this->string .= " MAIN.";
      if($field->isUnique()) $this->string .= " UNIQUE.";
      if(!$field->isAdmin()) $this->string .= " NOT ADMIN.";
      if($field->isHidden()) $this->string .= " HIDDEN.";
      if($field->getDefault()) $this->string .= " DEFAULT: {$field->getDefault()}.";
      $this->string .= "
";
    };

    return $this->string;
    
  }

  protected function end(){
    $this->string .= "";
  }







}
