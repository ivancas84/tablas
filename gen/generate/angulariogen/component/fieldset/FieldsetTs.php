<?php

require_once("generate/GenerateFileEntity.php");

class ComponentFieldsetTs extends GenerateFileEntity {

  protected $options = []; //opciones

  public function __construct(Entity $entity) {
    $file = $entity->getName("xx-yy") . "-fieldset.component.ts";
    $dir = PATH_ROOT . "tmp/component/fieldset/" . $entity->getName("xx-yy") . "-fieldset/";
    parent::__construct($dir, $file, $entity);
  }

  protected function start(){
    $this->string .= "import { Component } from '@angular/core';
import { FormBuilder, FormGroup, FormControl, Validators } from '@angular/forms';

import { DataDefinitionService } from '../../service/data-definition/data-definition.service';

import { FieldsetComponent } from '../../main/component/fieldset/fieldset.component';

@Component({
  selector: 'app-" . $this->entity->getName("xx-yy") . "-fieldset',
  templateUrl: './" . $this->entity->getName("xx-yy") . "-fieldset.component.html',
})
export class " . $this->entity->getName("XxYy") . "FieldsetComponent extends FieldsetComponent {
  constructor(protected fb: FormBuilder, protected dd: DataDefinitionService)  {
    super(fb, dd);
    this.entity = '" . $this->entity->getName() . "';
    this.fieldset = '" . $this->entity->getName() . "';
  }

";
  }

  protected function getters(){
    foreach($this->entity->getFieldsNf() as $field){
      $this->string .= "  get {$field->getName('xxYy')}() { return this.fieldsetForm.get('{$field->getName()}')}
";
    }
    $this->string .= "
";
  }

  protected function setChange(){
    $this->string .= "  setChange(){
";
    foreach($this->entity->getFieldsNf() as $field){
      if($field->isUnique()) $this->string .= "    this.changeUpdate('{$field->getName()}');
";

    }

    $this->string .= "  }
";
  }

  protected function initForm(){
    require_once("generate/angulariogen/component/fieldset/_InitForm.php");
    $gen = new ComponentFieldsetTs_initForm($this->entity);
    $this->string .= $gen->generate();
  }

  protected function formGroup(){
    require_once("generate/angulariogen/component/fieldset/_FormGroup.php");
    $gen = new ComponentFieldsetTs_formGroup($this->entity);
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


  protected function generateCode(){
    $this->start();
    $this->getters();
    $this->setChange();
    $this->formGroup();
    //$this->initForm();
    //$this->server();
    $this->end();
  }



}
