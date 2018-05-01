<?php

require_once("generate/Generate.php");

class DataDefinitionLoaderService extends GenerateFile {

  protected $structure; //estructura de tablas

  public function __construct(array $structure){
    $this->structure = $structure;
    parent::__construct(PATH_ROOT."src/app/service/data-definition/", "data-definition-loader.service.ts");

  }

  protected function importsStart(){
    $this->string .= "import { Injectable } from '@angular/core';

import { DataDefinition } from '../../main/service/data-definition/data-definition';
import { DataDefinitionService } from './data-definition.service';

";
  }

  protected function importsEntities(){
    foreach($this->structure as $entity){
      $this->string .= "import { " . $entity->getName("XxYy") . "DataDefinition } from './" . $entity->getName("xx-yy") . "/" . $entity->getName("xx-yy") . "-data-definition';
";
    }
  }

  protected function classStart(){
    $this->string .= "

@Injectable()
export class DataDefinitionLoaderService {
  getInstance(name: string, dd: DataDefinitionService): DataDefinition {
    switch(name) {
";
  }

  protected function classBody(){
    foreach($this->structure as $entity){
      $this->string .= "        case \"" . $entity->getName() . "\": { return new " . $entity->getName("XxYy") . "DataDefinition(dd); }
";
      }
  }

  protected function classEnd(){
    $this->string .= "     }
  }
}
";
  }


  protected function generateCode(){
    $this->importsStart();
    $this->importsEntities();
    $this->classStart();
    $this->classBody();
    $this->classEnd();
  }


}
