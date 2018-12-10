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
    require_once("generate/angulariogen/component/fieldsetArray/_FormGroup.php");
    $gen = new ComponentFieldsetArrayTs_formGroup($this->entity);
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
import { FieldsetArrayComponent } from '../../main/component/fieldset-array/fieldset-array.component';
import { DataDefinitionService } from '../../service/data-definition/data-definition.service';
import { {$this->entity->getName('XxYy')} } from '../../class/entity/{$this->entity->getName('xx-yy')}/{$this->entity->getName('xx-yy')}';

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
