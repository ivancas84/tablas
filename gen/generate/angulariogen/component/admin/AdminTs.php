<?php

require_once("generate/GenerateFileEntity.php");

class ComponentAdminTs extends GenerateFileEntity {

  public function __construct(Entity $entity) {
    $dir = PATH_ROOT . "tmp/component/admin/" . $entity->getName("xx-yy") . "-admin/";
    $file = $entity->getName("xx-yy") . "-admin.component.ts";
    parent::__construct($dir, $file, $entity);
  }


  protected function start(){
    $this->string .= "import { Component } from '@angular/core';
import { FormBuilder } from '@angular/forms';
import { ActivatedRoute } from '@angular/router';

import { DataDefinitionService } from '../../service/data-definition/data-definition.service';
import { AdminComponent } from '../../main/component/admin/admin.component';

import { MessageService } from '../../main/service/message/message.service';

@Component({
  selector: 'app-" . $this->entity->getName("xx-yy") . "-admin',
  templateUrl: './" . $this->entity->getName("xx-yy") . "-admin.component.html',
})
export class " . $this->entity->getName("XxYy") . "AdminComponent extends AdminComponent {

  constructor(protected fb: FormBuilder, protected route: ActivatedRoute, protected dd: DataDefinitionService, protected message: MessageService)  {
    super(fb, route, dd, message);
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
