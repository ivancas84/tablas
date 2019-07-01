<?php

require_once("generate/GenerateFileEntity.php");

class ComponentAdminTs extends GenerateFileEntity {

  public function __construct(Entity $entity) {
    $dir = PATH_GEN . "tmp/component/admin/" . $entity->getName("xx-yy") . "-admin/";
    $file = $entity->getName("xx-yy") . "-admin.component.ts";
    parent::__construct($dir, $file, $entity);
  }

  protected function hasRelationsFkTypeahead(){
    if(!$this->entity->hasRelationsFk()) return false;
    foreach($this->getEntity()->getFieldsFk() as $field) {    
      if($field->getSubtype() == "typeahead") return true;
    }
    return false;

  }

  protected function generateCode() {
    $this->start();
    $this->end();
  }

  protected function start() {
    $this->string .= "import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, FormControl, Validators } from '@angular/forms';
import { Location } from '@angular/common';
import { ActivatedRoute, Router } from '@angular/router';
";
    if($this->hasRelationsFkTypeahead()){
      $this->string .= "import { forkJoin } from 'rxjs';
import { first } from 'rxjs/operators';
";
    }

    $this->string .= "import { DataDefinitionService } from '../../service/data-definition/data-definition.service';
import { Entity } from '../../core/class/entity';
import { AdminComponent } from '../../core/component/admin/admin.component';
import { MessageService } from '../../core/service/message/message.service';
import { ValidatorsService } from '../../core/service/validators/validators.service';
import { {$this->entity->getName("XxYy")} } from '../../class/entity/{$this->entity->getName("xx-yy")}/{$this->entity->getName("xx-yy")}';

@Component({
  selector: 'app-" . $this->entity->getName("xx-yy") . "-admin',
  templateUrl: './" . $this->entity->getName("xx-yy") . "-admin.component.html',
})
export class " . $this->entity->getName("XxYy") . "AdminComponent extends AdminComponent implements OnInit {

  readonly entity: string = \"" . $this->entity->getName() . "\";

  constructor(protected fb: FormBuilder, protected route: ActivatedRoute, protected router: Router, protected location: Location, protected dd: DataDefinitionService, protected message: MessageService)  {
    super(fb, route, router, location, dd, message);
  }

";
  }



  protected function end() {
    $this->string .= "}

";
  }

}
