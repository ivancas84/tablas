<?php

require_once("class/model/Entity.php");
require_once("GenerateEntity.php");

class GenerateClassDataSqlMethodUploadSqlIndex extends GenerateEntity{

  protected $fieldsFile = array();

	public function __construct(Entity $entity) {
		parent::__construct($entity);

    foreach($this->getEntity()->getFields() as $field){
			if(!$field->isAdmin()) continue;
      switch($field->getSubtype()){
        case "file"; case "file_image": array_push($this->fieldsFile, $field); break;
      }
    }
	}

  protected function start(){
		$this->string .= "
  //***** @override *****
  public function uploadSqlIndex(array \$data, \$index){
    \$sql = \"\";
    \$pks = array();

";
	}


  protected function end(){
		$this->string .= "    return array(\"pks\" => \$pks, \"sql\" => \$sql);

  }
" ;
	}

  protected function body(){
    foreach($this->fieldsFile as $field){
      $this->string .= "    \$upload = \$this->uploadFieldSqlIndex(\"" . $field->getName() .  "_file\", \$index, \"upload/" . $this->getEntity()->getName("xxyy") . "/" . $field->getNameFormatDir() . "/\");
    \$pks[\"" . $field->getName() .  "\"] = (!empty(\$upload[\"pk\"])) ? \$upload[\"pk\"] : \$data[\"" . $field->getName() .  "\"];
    \$sql .= \$upload[\"sql\"];

";
    }
  }


	public function generate(){
    if(!count($this->fieldsFile)) return;
		$this->start();
    $this->body();
		$this->end();
	}

	public static function createAndGetString(Entity $entity){
		$self = new GenerateClassDataSqlMethodUploadSqlIndex($entity);
		$self->generate();
    return $self->getString();
	}
}
