<?php

require_once("generate/GenerateFile.php");

class LoaderService extends GenerateFile {

  protected $structure; //estructura de tablas

  public function __construct(array $structure){
    $this->structure = $structure;
    parent::__construct(PATH_ROOT."src/app/service/", "loader.service.ts");

  }

  protected function importsStart(){
    $this->string .= "import { Injectable } from '@angular/core';

import { Entity } from '../main/class/entity';
import { DataDefinition } from '../main/service/data-definition/data-definition';
import { DataDefinitionService } from './data-definition/data-definition.service';

";
  }

  protected function importsDataDefinition(){
    foreach($this->structure as $entity){
      $this->string .= "import { " . $entity->getName("XxYy") . "DataDefinition } from './data-definition/" . $entity->getName("xx-yy") . "/" . $entity->getName("xx-yy") . "-data-definition';
";
    }
  }

  protected function importsEntities(){
    foreach($this->structure as $entity){
      $this->string .= "import { " . $entity->getName("XxYy") . " } from '../class/entity/" . $entity->getName("xx-yy") . "/" . $entity->getName("xx-yy") . "';
";
    }
  }

  protected function classStart(){
    $this->string .= "

@Injectable()
export class LoaderService {
";
  }

  protected function classEnd(){
    $this->string .= "}
";
  }

  protected function dataDefinition(){
    require_once("generate/angulariogen/service/loader/_dataDefinition.php");
    $gen = new LoaderService_dataDefinition($this->structure);
    $this->string .= $gen->generate();
  }

  protected function entity(){
    require_once("generate/angulariogen/service/loader/_entity.php");
    $gen = new LoaderService_entity($this->structure);
    $this->string .= $gen->generate();
  }


  protected function generateCode(){
    $this->importsStart();
    $this->importsDataDefinition();
    $this->importsEntities();
    $this->classStart();
    $this->dataDefinition();
    $this->entity();
    $this->classEnd();
  }


}
