<?php

require_once("generate/GenerateFileEntity.php");

class ComponentFieldsetTs extends GenerateFileEntity {

  protected $options = []; //opciones

  public function __construct(Entity $entity) {
    $file = $entity->getName("xx-yy") . "-fieldset.component.ts";
    $dir = PATH_GEN . "tmp/component/fieldset/" . $entity->getName("xx-yy") . "-fieldset/";
    parent::__construct($dir, $file, $entity);
  }

  protected function generateCode(){
    $this->start();
    $this->getters();
    $this->formGroup();
    $this->end();
  }





  protected function start(){
    $this->string .= "import { Component } from '@angular/core';
import { FormBuilder, FormGroup, FormControl, Validators } from '@angular/forms';
import { DataDefinitionService } from '../../service/data-definition/data-definition.service';
import { FieldsetComponent } from '../../core/component/fieldset/fieldset.component';
import { {$this->entity->getName("XxYy")} } from '../../class/entity/{$this->entity->getName("xx-yy")}/{$this->entity->getName("xx-yy")}';


@Component({
  selector: 'app-" . $this->entity->getName("xx-yy") . "-fieldset',
  templateUrl: './" . $this->entity->getName("xx-yy") . "-fieldset.component.html',
})
export class " . $this->entity->getName("XxYy") . "FieldsetComponent extends FieldsetComponent {
  this.entityName: string = '" . $this->entity->getName() . "';
  this.fieldsetName: string = '" . $this->entity->getName() . "';

  constructor(protected fb: FormBuilder, protected dd: DataDefinitionService, protected validators: ValidatorsService) {
    super(fb, dd, validators);    
  }

";
  }

  protected function getters(){
    foreach($this->entity->getFieldsByType(["pk","nf","fk"]) as $field){
      if(!$field->isAdmin()) continue;
      $this->string .= "  get {$field->getName('xxYy')}() { return this.fieldset.get('{$field->getName()}')}
";
    }
    $this->string .= "
";
  }


  protected function formGroup(){
    require_once("generate/angulariogen/component/fieldset/_FormGroup.php");
    $gen = new FieldsetTs_formGroup($this->entity);
    $this->string .= $gen->generate();
  }




  protected function server(){
    require_once("generate/angulariogen/component/fieldset/_Server.php");
    $gen = new ComponentFieldsetTs_server($this->entity);
    $this->string .= $gen->generate();
  }

  protected function end(){
    $this->string .= "}
";
  }

}
