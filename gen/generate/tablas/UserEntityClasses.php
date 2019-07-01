<?php

require_once("generate/Generate.php");

class UserEntityClasses extends GenerateFile{

  public function __construct(){
    parent::__construct(PATH_ROOT."src/config/", "userEntityClasses.php");
  }

  protected function generateCode(){
    $this->string = "<?php
//Entitys y Fields definidos por el usuario se incluyen en este archivo
";

  }

}
