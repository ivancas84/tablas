<?php

require_once("generate/GenerateFileEntity.php");

class EntityDataDefinitionMain extends GenerateFileEntity {

  public function __construct(Entity $entity) {
    $dir = PATH_ROOT."src/app/service/data-definition/" . $entity->getName("xx-yy") . "/";
    $file = $entity->getName("xx-yy") . "-data-definition-main.ts";
    parent::__construct($dir, $file, $entity);
  }

  protected function generateCode(){
    $this->start();
    $this->storage();
    $this->options();
    $this->init();
    $this->initForm();

    $this->formGroup();
    //$this->initMain();
    $this->initFilters();

    $this->serverFilters();
    $this->server();

    $this->label();
    $this->end();

  }

  protected function start(){
    $this->string .= "import { FormBuilder, FormGroup, FormControl, Validators } from '@angular/forms';

import { Observable } from 'rxjs/Observable';
import { of } from 'rxjs/observable/of';
import 'rxjs/add/observable/forkJoin';

import { DataDefinition } from '../../../main/service/data-definition/data-definition';


export class " . $this->entity->getName("XxYy") . "DataDefinitionMain extends DataDefinition {
  entity: string = '{$this->entity->getName()}';

";
  }

  protected function storage(){
    require_once("generate/angulariogen/service/data-definition/entity-data-definition/_Storage.php");
    $gen = new EntityDataDefinition_Storage($this->entity);
    $this->string .= $gen->generate();
  }

  protected function init(){
    require_once("generate/angulariogen/service/data-definition/entity-data-definition/_Init.php");
    $gen = new EntityDataDefinition_Init($this->entity);
    $this->string .= $gen->generate();
  }

  protected function initMain(){
    require_once("generate/angulariogen/service/data-definition/entity-data-definition/_InitMain.php");
    $gen = new EntityDataDefinition_InitMain($this->entity);
    $this->string .= $gen->generate();
  }

  protected function initFilters(){
    require_once("generate/angulariogen/service/data-definition/entity-data-definition/_InitFilters.php");
    $gen = new EntityDataDefinition_InitFilters($this->entity);
    $this->string .= $gen->generate();
  }

  protected function initForm(){
    require_once("generate/angulariogen/service/data-definition/entity-data-definition/_InitForm.php");
    $gen = new EntityDataDefinition_InitForm($this->entity);
    $this->string .= $gen->generate();
  }

  protected function serverFilters(){
    require_once("generate/angulariogen/service/data-definition/entity-data-definition/_ServerFilters.php");
    $gen = new EntityDataDefinition_ServerFilters($this->entity);
    $this->string .= $gen->generate();
  }

  protected function options(){
    require_once("generate/angulariogen/service/data-definition/entity-data-definition/_Options.php");
    $gen = new EntityDataDefinition_Options($this->entity);
    $this->string .= $gen->generate();
  }

  protected function formGroup(){
    require_once("generate/angulariogen/service/data-definition/entity-data-definition/_FormGroup.php");
    $gen = new EntityDataDefinition_FormGroup($this->entity);
    $this->string .= $gen->generate();
  }

  protected function label(){
    require_once("generate/angulariogen/service/data-definition/entity-data-definition/_Label.php");
    $gen = new EntityDataDefinition_Label($this->entity);
    $this->string .= $gen->generate();
  }


  protected function server(){
    require_once("generate/angulariogen/service/data-definition/entity-data-definition/_Server.php");
    $gen = new EntityDataDefinition_Server($this->entity);
    $this->string .= $gen->generate();
  }

  protected function end(){
    $this->string .= "}
";
  }



}
