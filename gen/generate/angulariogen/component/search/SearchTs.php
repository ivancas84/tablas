
<?php
require_once("generate/GenerateFileEntity.php");

class ComponentSearchTs extends GenerateFileEntity {


  public function __construct(Entity $entity) {
    $dir = PATH_ROOT . "tmp/component/search/" . $entity->getName("xx-yy") . "-search/";
    $file = $entity->getName("xx-yy") . "-search.component.ts";
    parent::__construct($dir, $file, $entity);
  }


  protected function generateCode(){
    $this->start();
    $this->initFilters();
    $this->onSubmit();
    $this->end();
  }


  protected function start(){
    $this->string .= "import { Component } from '@angular/core';
import { FormBuilder, FormGroup } from '@angular/forms';
import { Observable } from 'rxjs/Observable';
import { forkJoin } from 'rxjs';

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
  }

";
  }
  protected function initFilters(){
    require_once("generate/angulariogen/component/search/_InitFilters.php");
    $gen = new ComponentSearchTs_initFilters($this->entity);
    $this->string .= $gen->generate();
  }

  protected function onSubmit(){
    require_once("generate/angulariogen/component/search/_OnSubmit.php");
    $gen = new ComponentSearchTs_onSubmit($this->entity);
    $this->string .= $gen->generate();
  }

  protected function end(){
    $this->string .= "}
";
  }





}
