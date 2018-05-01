<?php

require_once("generate/GenerateFileEntity.php");

class ComponentSearchTs extends GenerateFileEntity {

  protected $options = []; //opciones

  public function __construct(Entity $entity) {
    $dir = PATH_ROOT . "tmp/component/search/" . $entity->getName("xx-yy") . "-search/";
    $file = $entity->getName("xx-yy") . "-search.component.ts";
    parent::__construct($dir, $file, $entity);
  }

  protected function generateCode(){
    $this->string .= "import { Component } from '@angular/core';
import { FormBuilder } from '@angular/forms';

import { SearchComponent } from '../../main/component/search/search.component';
import { DataDefinitionService } from '../../service/data-definition/data-definition.service';
import { RouterService } from '../../main/service/router/router.service';

@Component({
  selector: 'app-" . $this->entity->getName("xx-yy") . "-search',
  templateUrl: './" . $this->entity->getName("xx-yy") . "-search.component.html',
})
export class " . $this->entity->getName("XxYy") . "SearchComponent extends SearchComponent {
  constructor(protected fb: FormBuilder, protected dd: DataDefinitionService, protected router: RouterService)  {
    super(fb, dd, router);
    this.entity = '" . $this->entity->getName() . "';
    this.url =  '" . $this->entity->getName("xx-yy") . "-show';
  }
}
";
  }



}
