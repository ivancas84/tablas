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
    $this->getters();
    $this->formGroup();
    $this->end();
  }

  protected function getters(){
    foreach($this->entity->getFieldsByType(["nf","fk"]) as $field){
      if(!$field->isAdmin()) continue;
      $this->string .= "  {$field->getName('xxYy')}(i) { return this.fieldset[i].get('{$field->getName()}')}
";
    }
    $this->string .= "

";
  }

  protected function start(){
    $this->string .= "import { Component } from '@angular/core';
import { FormBuilder, FormGroup, FormControl, Validators } from '@angular/forms';
import { FieldsetArrayComponent } from 'src/app/core/component/fieldset-array/fieldset-array.component';
import { DataDefinitionService } from 'src/app/service/data-definition/data-definition.service';
import { ValidatorsService } from 'src/app/core/service/validators/validators.service';
import { {$this->entity->getName('XxYy')} } from 'src/app/class/entity/{$this->entity->getName('xx-yy')}/{$this->entity->getName('xx-yy')}';

@Component({
  selector: 'app-" . $this->entity->getName("xx-yy") . "-fieldset-array',
  templateUrl: './" . $this->entity->getName("xx-yy") . "-fieldset-array.component.html',
})
export class " . $this->entity->getName("XxYy") . "FieldsetArrayComponent extends FieldsetArrayComponent {
  entityName: string = '" . $this->entity->getName() . "';
  fieldsetName: string = '" . $this->entity->getName() . "_';

  constructor(protected fb: FormBuilder, protected dd: DataDefinitionService, protected validators: ValidatorsService) {
    super(fb, dd, validators);    
  }

";
  }

  protected function end(){
    $this->string .= "}
";
  }


}
