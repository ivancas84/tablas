<?php


class AppGridTs extends GenerateFileEntity {

  public function __construct(Entity $entity) {
    $dir = PATH_ROOT . "src/app/" . $entity->getName("xx-yy") . "-grid/";
    $file = $entity->getName("xx-yy") . "-grid.ts";
    parent::__construct($dir, $file, $entity);
  }


  protected function start(){
    $this->string .= "import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-" . $this->entity->getName("xx-yy") . "-grid',
  templateUrl: './" . $this->entity->getName("xx-yy") . "-grid.component.html',
  styleUrls: ['./" . $this->entity->getName("xx-yy") . "-grid.component.css']
})
export class " . $this->entity->getName("XxYy") . "GridComponent implements OnInit {

  constructor() { }

  ngOnInit() {
  }

}
";
  }



  //***** @override *****
  protected function generateCode() {
    $this->start();
  }

}
