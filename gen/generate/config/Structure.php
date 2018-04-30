<?php

require_once("generate/GenerateFile.php");
require_once("class/db/My.php");

/**
 * Generar estructura
 */
class GenerateConfigStructure extends GenerateFile {
  
  protected $tablesInfo; //array. Nombres de tablas
  
  public function __construct(array $tablesInfo) {
    $this->tablesInfo = $tablesInfo;
    parent::__construct(PATH_ROOT."api/config/","structure.php");
  }
  
  protected function generateCode() {
    $this->string .= "<?php
      
require_once(\"config/entityClasses.php\");

\$structure = array ( 
" ;
    
    foreach ( $this->tablesInfo as $tableInfo ) {
      $this->string .= "  new " . snake_case_to("XxYy", $tableInfo["name"]) . "Entity,
" ;
    }

    $this->string .= ");
      
  Entity::setStructure(\$structure);

";      
  }
  
 
}

