<?php

require_once("generate/GenerateFile.php");

class IncludeValuesClasses extends GenerateFile{

  protected $structure; //estructura de tablas
  public function __construct(array $structure){
    $this->structure = $structure;
    parent::__construct(PATH_ROOT."config/", "valuesClasses.php");
  }

  protected function generateCode(){
    $this->string = "<?php
";

    foreach ( $this->structure as $table ) {

      $this->string .= "require_once(\"class/model/values/" . $table->getName("xxYy") . "/" . $table->getName("XxYy") . ".php\");
" ;
    }
  }


}
