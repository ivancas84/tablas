<?php

require_once("GenerateFile.php");

class IncludeModelClasses extends GenerateFile{

  protected $structure; //estructura de tablas
  public function __construct(array $structure){
    $this->structure = $structure;
    parent::__construct($_SERVER["DOCUMENT_ROOT"]."/".PATH_ROOT."config/", "modelClasses.php");
  }

  protected function generateCode(){
    $this->string = "<?php
";

    foreach ( $this->structure as $table ) {

      $this->string .= "require_once(\"class/model/sql/" . $table->getName("xxYy") . "/" . $table->getName("XxYy") . ".php\");
require_once(\"class/model/sqlo/" .  $table->getName("xxYy") . "/" . $table->getName("XxYy") . ".php\");

" ;
    }
  }


}
