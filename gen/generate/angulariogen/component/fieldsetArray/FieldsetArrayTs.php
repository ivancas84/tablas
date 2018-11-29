<?php

require_once("generate/GenerateFileEntity.php");

class ComponentFieldsetArrayTs extends GenerateFileEntity {

  protected $options = []; //opciones

  public function __construct(Entity $entity) {
    $file = $entity->getName("xx-yy") . "-fieldset-array.component.ts";
    $dir = PATH_GEN . "tmp/component/fieldset-array/" . $entity->getName("xx-yy") . "-fieldset-array/";
    parent::__construct($dir, $file, $entity);
  }

  protected function formGroup(){
    require_once("generate/angulariogen/component/fieldset/_FormGroup.php");
    $gen = new ComponentFieldsetTs_formGroup($this->entity);
    $this->string .= $gen->generate();
  }

  protected function generateCode(){
    $this->start();
    $this->formGroup();
    $this->end();
  }


  protected function start(){
    $this->string .= "import { Component } from '@angular/core';
import { FormBuilder, FormGroup, FormControl, Validators } from '@angular/forms';

import { DataDefinitionService } from '../../service/data-definition/data-definition.service';

import { FieldsetArrayComponent } from '../../main/component/fieldset-array/fieldset-array.component';

@Component({
  selector: 'app-" . $this->entity->getName("xx-yy") . "-fieldset-array',
  templateUrl: './" . $this->entity->getName("xx-yy") . "-fieldset-array.component.html',
})
export class " . $this->entity->getName("XxYy") . "FieldsetArrayComponent extends FieldsetArrayComponent {
  constructor(protected fb: FormBuilder, protected dd: DataDefinitionService)  {
    super(fb, dd);
    this.entity = '" . $this->entity->getName() . "';
    this.fieldset = '" . $this->entity->getName() . "';
  }
";
  }

  protected function end(){
    $this->string .= "}
";
  }


}
