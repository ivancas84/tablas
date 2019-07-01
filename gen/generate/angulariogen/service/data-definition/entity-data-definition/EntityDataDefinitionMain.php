<?php

require_once("generate/GenerateFileEntity.php");

class EntityDataDefinitionMain extends GenerateFileEntity {

  public function __construct(Entity $entity) {
    $dir = PATH_GEN."src/app/service/data-definition/" . $entity->getName("xx-yy") . "/";
    $file = $entity->getName("xx-yy") . "-data-definition-main.ts";
    parent::__construct($dir, $file, $entity);
  }

  protected function generateCode(){
    $this->start();
    $this->storage();
    $this->options();
    $this->label();
    $this->end();

  }

  protected function start() {
    $this->string .= "import { FormBuilder, FormGroup, FormControl, Validators } from '@angular/forms';

import { Observable } from 'rxjs/Observable';
import { of } from 'rxjs/observable/of';
import { forkJoin } from 'rxjs';

import { DataDefinition } from '../../../core/service/data-definition/data-definition';


export class " . $this->entity->getName("XxYy") . "DataDefinitionMain extends DataDefinition {
  entity: string = '{$this->entity->getName()}';

";
  }

  protected function storage(){
    require_once("generate/angulariogen/service/data-definition/entity-data-definition/_Storage.php");
    $gen = new EntityDataDefinition_Storage($this->entity);
    $this->string .= $gen->generate();
  }

  protected function label(){
    require_once("generate/angulariogen/service/data-definition/entity-data-definition/_Label.php");
    $gen = new EntityDataDefinition_Label($this->entity);
    $this->string .= $gen->generate();
  }


  protected function options(){
    require_once("generate/angulariogen/service/data-definition/entity-data-definition/_Options.php");
    $gen = new EntityDataDefinition_Options($this->entity);
    $this->string .= $gen->generate();
  }

  protected function end(){
    $this->string .= "}
";
  }



}
