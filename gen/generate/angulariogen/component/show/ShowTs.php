<?php

require_once("generate/GenerateFileEntity.php");

class ComponentShowTs extends GenerateFileEntity {

  public function __construct(Entity $entity) {
    $dir = PATH_ROOT . "tmp/component/show/" . $entity->getName("xx-yy") . "-show/";
    $file = $entity->getName("xx-yy") . "-show.component.ts";
    parent::__construct($dir, $file, $entity);
  }


  protected function start(){
    $this->string .= "import { Component } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { DataDefinitionService } from '../../service/data-definition/data-definition.service';
import { ShowComponent } from '../../main/component/show/show.component';

@Component({
  selector: 'app-" . $this->entity->getName("xx-yy") . "-show',
  templateUrl: './" . $this->entity->getName("xx-yy") . "-show.component.html',
})
export class " . $this->entity->getName("XxYy") . "ShowComponent extends ShowComponent {

  constructor(protected dd: DataDefinitionService, protected route: ActivatedRoute) {
    super(dd, route);
    this.entity = \"" . $this->entity->getName() . "\";
  }

}

";
  }



  //***** @override *****
  protected function generateCode() {
    $this->start();
  }

}
