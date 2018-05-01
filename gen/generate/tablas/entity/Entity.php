<?php

require_once("function/snake_case_to.php");
require_once("generate/Generate.php");

class ClassEntity extends GenerateFile {

  public function __construct($tableName) {
    $this->tableName = $tableName;
    $dir = PATH_ROOT."api/class/model/entity/" . snake_case_to("xxYy", $this->tableName) . "/";
    $file = snake_case_to("XxYy", $this->tableName).".php";
    parent::__construct($dir, $file);
  }



  protected function generateCode(){
    $this->string .= "<?php

require_once(\"class/model/entity/" . snake_case_to("xxYy", $this->tableName) . "/Main.php\");

class " . snake_case_to("XxYy", $this->tableName) . "Entity extends " . snake_case_to("XxYy", $this->tableName) . "EntityMain {

}
";
  }


}
