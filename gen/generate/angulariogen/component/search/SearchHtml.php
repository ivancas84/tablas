<?php

require_once("generate/GenerateFileEntity.php");

class ComponentSearchHtml extends GenerateFileEntity {

  public function __construct(Entity $entity, $directorio = null){
    $file = $entity->getName("xx-yy") . "-search.component.html";
    if(!$directorio) $directorio = PATH_GEN . "tmp/component/search/" . $entity->getName("xx-yy") . "-search/";
    parent::__construct($directorio, $file, $entity);
  }


  public function generateCode() {
    $this->start();

    $this->selectOptionsStart();
    $this->selectOptionsPk();
    $this->selectOptionsNf();
    $this->selectOptionsRecursive($this->entity);
    $this->selectOptionsEnd();

    $this->switchStart();
    $this->switchPk();
    $this->switchNf();
    $this->switchRecursive($this->entity);
    $this->switchEnd();

    $this->end();
  }


  protected function start(){
    $this->string .= "<ngb-accordion #acc=\"ngbAccordion\">
  <ngb-panel>
    <ng-template ngbPanelTitle>
      <span>Opciones</span>
    </ng-template>
    <ng-template ngbPanelContent>
      <form [formGroup]=\"searchForm\" novalidate (ngSubmit)=\"onSubmit()\" >
        <div formArrayName=\"filters\">
          <div class=\"form-row align-items-center\" *ngFor=\"let filter of filters.controls; let i=index\" [formGroupName]=\"i\">
";
  }






  protected function selectOptionsStart(){
    $this->string .= "            <div class=\"col-4\">
              <select class=\"form-control form-control-sm\" formControlName=\"field\">
                <option value=\"\">--Campo--</option>
";
  }

  protected function selectOptionsPk(){
   $field = $this->entity->getPk();
   $this->string .= "                <option value=\"" . $field->getName() . "\">" . $field->getEntity()->getName("Xx Yy") . "</option>
";
  }

  protected function selectOptionsNf(){
   $fields = $this->entity->getFieldsNf();
   foreach($fields as $field) {
     if($field->isAggregate()) continue;
      $this->string .= "                <option value=\"" . $field->getName() . "\">" . $field->getName("Xx Yy") . "</option>
";
    }
  }

  protected function selectOptionsRecursive(Entity $entity, array $tablesVisited = NULL, $prefix = ""){
    if(is_null($tablesVisited)) $tablesVisited = array($entity->getName());
    $this->selectOptionsRelation($entity, $prefix);
    $this->selectOptionsFk($entity, $tablesVisited, $prefix);
  }

  protected function  selectOptionsFk(Entity $entity, array $tablesVisited, $prefix){
    $fk = $entity->getFieldsFkNotReferenced($tablesVisited);
    foreach ($fk as $field) {
      array_push($tablesVisited, $entity->getName());
      $this->selectOptionsRecursive($field->getEntityRef(), $tablesVisited, $prefix . $field->getAlias() . "_") ;
    }
  }

  protected function selectOptionsRelation(Entity $entity, $prefix){
    $fields = $entity->getFieldsFk();
    foreach($fields as $field) $this->string .= "                <option value=\"" . $prefix . $field->getName() . "\">" . $field->getName("Xx Yy") . "</option>
";
  }

  protected function selectOptionsEnd(){
    $this->string .= "              </select>
            </div>
";
  }


  protected function switchStart(){
    $this->string .= "            <div class=\"col-6\" [ngSwitch]=\"f(i)\">
";
  }

  protected function switchPk(){
    $field = $this->entity->getPk();
    $this->string .= "              <div class=\"form-row\" *ngSwitchCase=\"'" . $field->getName() . "'\">
                <div class=\"col-sm-3\">
                  <select class=\"form-control form-control-sm\" formControlName=\"option\">
                    <option value=\"=\">=</option>
                    <option value=\"!=\">&ne;</option>
                  </select>
                </div>
                <div class=\"col\">
                  <app-filter-typeahead [entity]=\"'" . $this->entity->getName() . "'\" [filter]=\"filter\" ></app-filter-typeahead>
                </div>
              </div>
";
  }


  protected function switchNf(){
    $fieldsNf = $this->entity->getFieldsNf();

    foreach($fieldsNf as $field) {
      if($field->isAggregate()) continue;

      switch($field->getSubtype()) {
        //case "date": $this->date($field); break;
        case "checkbox": $this->checkbox($field); break;

        default: $this->defecto($field); break;
      }
    }
  }

  protected function switchRecursive(Entity $entity, array $tablesVisited = NULL, $prefix = ""){
   if(is_null($tablesVisited)) $tablesVisited = array($entity->getName());

   $this->string .= $this->switchRelation($entity, $prefix);

   $this->switchFk($entity, $tablesVisited, $prefix);
  }


  protected function switchRelation(Entity $entity, $prefix){
   $fieldsFk = $entity->getFieldsFk();

    foreach($fieldsFk as $field) {
     switch($field->getSubtype()){
       case "typeahead": $this->typeahead($field, $field->getEntityRef(), $prefix); break;
       case "select": $this->select($field, $field->getEntityRef(), $prefix); break;
     }
   }
  }

  protected function switchFk(Entity $entity, array $tablesVisited, $prefix) {
   $fk = $entity->getFieldsFkNotReferenced($tablesVisited);
   foreach ($fk as $field) {
     array_push($tablesVisited, $entity->getName());
     $this->string .= $this->switchRecursive($field->getEntityRef(), $tablesVisited, $prefix . $field->getAlias() . "_") ;
   }
  }


  protected function switchEnd(){
   $this->string .= "              <div *ngSwitchDefault>Seleccione campo</div>
            </div>
";
  }






  protected function defecto(Field $field){
    $this->string .= "              <div class=\"form-row\" *ngSwitchCase=\"'{$field->getName()}'\">
                <div class=\"col-sm-3\">
                  <select class=\"form-control form-control-sm\" formControlName=\"option\">
                    <option value=\"=\">=</option>
                    <option value=\"!=\">&ne;</option>
                    <option value=\"<=\">&le;</option>
                    <option value=\">=\">&ge;</option>
";

    if(!$field->isNotNull())
      $this->string .= "                    <option value=\"1\">S</option>
                    <option value=\"0\">N</option>
";

      $this->string .= "                  </select>
                </div>
                <div class=\"col\">
                  <input class=\"form-control form-control-sm\" formControlName=\"value\">
                </div>
              </div>
";

  }

  protected function checkbox(Field $field){
    $this->string .= "              <div class=\"form-row\" *ngSwitchCase=\"'{$field->getName()}'\">
                <div class=\"col\">
                  <input class=\"form-control form-control-sm\" type=\"checkbox\" formControlName=\"option\">
                </div>
              </div>
";
  }

  protected function typeahead(Field $field, Entity $entity, $prefix = ""){
    $this->string .= "              <div class=\"form-row\" *ngSwitchCase=\"'{$prefix}{$field->getName()}'\">
                <div class=\"col-sm-3\">
                  <select class=\"form-control form-control-sm\" formControlName=\"option\">
                    <option value=\"=\">=</option>
                    <option value=\"!=\">&ne;</option>
                  </select>
                </div>
                <div class=\"col\">
                  <app-filter-typeahead [entity]=\"'" . $field->getEntityRef()->getName() . "'\" [filter]=\"filter\" ></app-filter-typeahead>
                </div>
              </div>
";

  }

  protected function select(Field $field, Entity $entity, $prefix = ""){
    $this->string .= "              <div class=\"form-row\" *ngSwitchCase=\"'{$prefix}{$field->getName()}'\">
                <div class=\"col-sm-3\">
                  <select class=\"form-control form-control-sm\" formControlName=\"option\">
                    <option value=\"=\">=</option>
                    <option value=\"!=\">&ne;</option>
                  </select>
                </div>
                <div class=\"col\">
                  <select class=\"form-control form-control-sm\" formControlName=\"value\" >
                    <option *ngFor=\"let opt of options." . $field->getEntityRef()->getName() . "\" [value]=\"opt.id\" >{{opt.id | label:\"{$field->getEntityRef()->getName()}\"}}</option>
                  </select>
                </div>
              </div>
";

  }


  protected function end(){
    $this->string .= "            <div class=\"col-2\">
              <button type=\"button\" class=\"btn btn-danger btn-sm\" (click)=\"removeFilter(i)\"><span class=\"oi oi-x\"></span></button>
            </div>
          </div>
        </div> <!-- formArrayName=\"filters\" -->

        <div class=\"form-row\">
          <div class=\"col-4\">
            <input class=\"form-control form-control-sm\" formControlName=\"search\" placeholder=\"Buscar\" >
          </div>
          <div class=\"col\">
            <button type=\"button\" class=\"btn btn-info btn-sm\" (click)=\"addFilter()\"><span class=\"oi oi-layers\"></span></button>
            <button type=\"submit\" class=\"btn btn-primary btn-sm\"><span class=\"oi oi-magnifying-glass\"></span></button>
          </div>
        </div>
      </form>
    </ng-template>
  </ngb-panel>
</ngb-accordion>
";
  }






















}
