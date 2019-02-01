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
    //$this->selectOptionsRecursive($this->entity);
    $this->selectOptionsEnd();

    $this->switchStart();
    $this->switchPk();
    $this->switchNf();
    //$this->switchRecursive($this->entity);
    $this->switchEnd();

    $this->end();
  }


  protected function start(){
    $this->string .= "<form [formGroup]=\"searchForm\" novalidate (ngSubmit)=\"onSubmit()\" >
  <div formArrayName=\"filters\">
    <div class=\"form-inline m-1 border bg-light\" *ngFor=\"let filter of filters.controls; let i=index\" [formGroupName]=\"i\" >
";
  }






  protected function selectOptionsStart(){
    $this->string .= "      <select class=\"form-control form-control-sm\" formControlName=\"field\">
        <option value=\"\">--Campo--</option>
";
  }

  protected function selectOptionsPk(){
   $field = $this->entity->getPk();
   $this->string .= "        <option value=\"" . $field->getName() . "\">" . $field->getEntity()->getName("Xx Yy") . "</option>
";
  }

  protected function selectOptionsNf(){
   $fields = $this->entity->getFieldsNf();
   foreach($fields as $field) $this->string .= "        <option value=\"" . $field->getName() . "\">" . $field->getName("Xx Yy") . "</option>
";
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
    foreach($fields as $field) $this->string .= "        <option value=\"" . $prefix . $field->getName() . "\">" . $field->getName("Xx Yy") . "</option>
";
  }

  protected function selectOptionsEnd(){
    $this->string .= "      </select>
";
  }






  protected function switchStart(){
    $this->string .= "
      <span [ngSwitch]=\"filter.get('field').value\">
";
  }

  protected function switchPk(){
    $field = $this->entity->getPk();
    $this->string .= "        <span *ngSwitchCase=\"'" . $field->getName() . "'\">
          <select class=\"form-control form-control-sm\" formControlName=\"option\">
            <option value=\"=\">=</option>
            <option value=\"!=\">&ne;</option>
          </select>
          <app-filter-typeahead [entity]=\"'" . $this->entity->getName() . "'\" [filter]=\"filter\" ></app-filter-typeahead>
        </span>

";
  }


  protected function switchNf(){
    $fieldsNf = $this->entity->getFieldsNf();

    foreach($fieldsNf as $field) {
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
   //$this->switchFieldsU_($entity, $tablesVisited, $prefixTable, $prefixField);
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
   $this->string .= "        <span *ngSwitchDefault>Seleccione campo</span>
      </span>
";
  }






  protected function defecto(Field $field){
    $this->string .= "        <span *ngSwitchCase=\"'" . $field->getName() . "'\">
          <select class=\"form-control form-control-sm\" formControlName=\"option\">
            <option value=\"=\">=</option>
            <option value=\"!=\">&ne;</option>
            <option value=\"<=\">&le;</option>
            <option value=\">=\">&ge;</option>
";

    if(!$field->isNotNull())
      $this->string .= "            <option value=\"1\">S</option>
            <option value=\"0\">N</option>
";

      $this->string .= "          </select>
          <input class=\"form-control form-control-sm\" formControlName=\"value\">
        </span>

";

  }

  protected function checkbox(Field $field){
    $this->string .= "        <span *ngSwitchCase=\"'" . $field->getName() . "'\">
          <input class=\"form-control form-control-sm\" type=\"checkbox\" formControlName=\"option\">
        </span>
";
  }

  protected function typeahead(Field $field, Entity $entity, $prefix = ""){
    $this->string .= "        <span *ngSwitchCase=\"'" . $prefix . $field->getName() . "'\">
          <select class=\"form-control form-control-sm\" ng-model=\"row.option\" ng-change=\"filterConfig()\">
            <option value=\"=\">=</option>
            <option value=\"!=\">&ne;</option>
";
   if(!$field->isNotNull())
     $this->string .= "            <option value=\"1\">S</option>
            <option value=\"0\">N</option>
";
   $this->string .= "          </select>
          <app-filter-typeahead [entity]=\"'" . $this->entity->getName() . "'\" [filter]=\"filter\" ></app-filter-typeahead>
        </span>

";

  }

  protected function select(Field $field, Entity $entity, $prefix = ""){
    $this->string .= "        <span *ngSwitchCase=\"'" . $prefix . $field->getName() . "'\">
          <select class=\"form-control form-control-sm\" ng-model=\"row.option\" ng-change=\"filterConfig()\">
            <option value=\"=\">=</option>
            <option value=\"!=\">&ne;</option>
";
   if(!$field->isNotNull())
     $this->string .= "            <option value=\"1\">S</option>
            <option value=\"0\">N</option>
";
   $this->string .= "          </select>
          <select class=\"form-control form-control-sm\" formControlName=\"value\" >
            <option *ngFor=\"let opt of options." . $field->getEntityRef()->getName() . "\" [value]=\"opt.id\" >{{opt.id | label:\"{$field->getEntityRef()->getName()}\"}}</option>
          </select>
        </span>

";

  }


  protected function end(){
    $this->string .= "        <button type=\"button\" class=\"btn btn-danger btn-sm\" (click)=\"removeFilter(i)\"><span class=\"oi oi-x\"></span></button>
    </div>
  </div>

  <div class=\"form-inline m-1 border bg-light\">
    <input class=\"form-control form-control-sm\" formControlName=\"search\" placeholder=\"Buscar\" >
    <button type=\"button\" class=\"btn btn-info btn-sm\" (click)=\"addFilter()\"><span class=\"oi oi-layers\"></span></button>
    <button type=\"submit\" class=\"btn btn-primary btn-sm\"><span class=\"oi oi-magnifying-glass\"></span></button>
  </div>
</form>
";
  }

















//TODO 17/2/2018:
//El codigo siguiente corresponde a la version anterior y aun no ha sido refactorizado a angular.io







  protected function date(Field $field){
   $this->string .= "        <span ng-switch-when=\"" . $field->getName() . "\">
         <select ng-model=\"row.option\" ng-change=\"filterConfig()\">
           <option value=\"=\">=</option>
           <option value=\"!=\">&ne;</option>
           <option value=\"<=\">&le;</option>
           <option value=\">=\">&ge;</option>
  ";
   if(!$field->isNotNull())
     $this->string .= "            <option value=\"1\">S</option>
           <option value=\"0\">N</option>
  ";
   $this->string .= "          </select>
         <input ng-show=\"showValue(\$index)\"  placeholder=\"dd/mm/aaaa\" type=\"text\" uib-datepicker-popup=\"dd/MM/yyyy\" uib-datepicker-options=\"datePickerOptions\" ng-model=\"row.value\" is-open=\"row.picker\" ng-change=\"filterConfig()\" />
         <button type=\"button\" class=\"btn btn-xs btn-default\" ng-click=\"row.picker = true\"><i class=\"glyphicon glyphicon-calendar\"></i></button>
         <button class=\"btn btn-sm btn-danger\" type=\"button\" ng-click=\"deleteFilter(\$index)\"/>Eliminar</button>
       </span>
  ";

  }











}
