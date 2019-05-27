<?php


class AppOptionsComponentHtml extends GenerateFile {

  public function __construct(array $structure) {
    $dir = PATH_GEN . "src/app/options/";
    $file = "options.component.html";
    parent::__construct($dir, $file);
  }


  protected function start() {
    $this->string .= "<p>Opciones</p>
";
  }



  //***** @override *****
  protected function generateCode() {
    $this->start();
  }

}
