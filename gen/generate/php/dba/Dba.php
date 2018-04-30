<?php

require_once("generate/GenerateFile.php");

class ClassDba extends GenerateFile {

  public function __construct() {
    $dir = PATH_ROOT."api/class/model/";
    $file = "Dba.php";
    parent::__construct($dir, $file);
  }



  protected function generateCode(){
    $this->string .= "<?php

require_once(\"class/model/DbaMain.php\");

class Dba extends DbaMain {

}
";
  }


}
