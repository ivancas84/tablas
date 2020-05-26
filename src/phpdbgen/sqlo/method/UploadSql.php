<?php

require_once("class/model/Entity.php");
require_once("GenerateEntity.php");

class GenerateClassDataSqlMethodUploadSql extends GenerateEntity{

  protected $fieldsFile = array();

  public function __construct(Entity $entity) {
    parent::__construct($entity);

    foreach($this->getEntity()->getFields() as $field){
      if(!$field->isAdmin()) continue;
      if ($field->getSubtype() == "file" || $field->getSubtype() == "file_image"){
        array_push($this->fieldsFile, $field);
      }
    }
  }

  protected function start(){
    $this->string .= "
  //***** @override *****
  public function uploadSql(array \$data){
    \$sql = \"\";
    \$ids = array();

";
  }


  protected function end(){
    $this->string .= "    return array(\"ids\" => \$ids, \"sql\" => \$sql);

  }
" ;
  }

  protected function body(){
    foreach($this->fieldsFile as $field){
      if(!$field->isAdmin()) continue;
      $this->string .= "    \$upload = \$this->uploadFieldSql(\"" . $field->getName() .  "_file\", \"upload/" . $this->getEntity()->getName("xxyy") . "/" . $field->getNameFormatDir() . "/\");
    \$ids[\"" . $field->getName() .  "\"] = (!empty(\$upload[\"id\"])) ? \$upload[\"id\"] : \$data[\"" . $field->getName() .  "\"];
    \$sql .= \$upload[\"sql\"];

";
    }
  }


  public function generate(){
    if(!count($this->fieldsFile)) return;
    $this->start();
    $this->body();
    $this->end();
    return $this->string;
  }

}
