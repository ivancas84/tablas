<?php

require_once("generate/GenerateFileEntity.php");

class ComponentTableTs extends GenerateFileEntity {

  public function __construct(Entity $entity) {
    $dir = PATH_ROOT . "tmp/component/table/" . $entity->getName("xx-yy") . "-table/";
    $file = $entity->getName("xx-yy") . "-table.component.ts";
    parent::__construct($dir, $file, $entity);
  }


  protected function start(){
    $this->string .= "import { Component } from '@angular/core';
import { NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { TableComponent } from '../../main/component/table/table.component';
import { DataDefinitionService } from '../../service/data-definition/data-definition.service';

@Component({
  selector: 'app-" . $this->entity->getName("xx-yy") . "-table',
  templateUrl: './" . $this->entity->getName("xx-yy") . "-table.component.html',
})
export class " . $this->entity->getName("XxYy") . "TableComponent extends TableComponent {

  constructor(protected dd: DataDefinitionService, protected modalService: NgbModal) {
    super(dd, modalService);
    this.entity = '" . $this->entity->getName() . "';
  }

}

";
  }



  //***** @override *****
  protected function generateCode() {
    $this->start();
  }

}