<?php

require_once("generate/GenerateFileEntity.php");

class FieldsetFieldsTemplate extends GenerateFileEntity {
  

  public function __construct(Entity $entity, $dir = null){
    if(!$dir) $dir = PATH_GEN . "component/fieldsetFields/" . $entity->getName("xxYy") . "/"; 
    $file = "Template.html";
    parent::__construct($dir, $file, $entity);
  }
  
  
  protected function start(){
    $this->string .= "    <fieldset ng-disabled=\"disabled\">
      <legend>" . $this->getEntity()->getName("Xx Yy") . "</legend>
";
  }
  

 
  
  protected function end(){
    $this->string .= "    </fieldset>
";
  }


  
  
  //***** date *****
  protected function date(Field $field){
    $this->string .= "      <div class=\"form-group\">
        <label class=\"control-label col-sm-2\">" . $field->getName("Xx Yy") . ":</label>
        <div class=\"col-sm-3\">
          <input class=\"form-control\" placeholder=\"dd/mm/aaaa\" type=\"text\" uib-datepicker-popup=\"dd/MM/yyyy\" uib-datepicker-options=\"datePickerOptions\" ng-model=\"" . $this->getEntity()->getName() . "." . $field->getName() . ".value\" is-open=\"" . $field->getName() . "_picker\" ng-blur=\"check('" . $field->getName() . "')\"/>
        </div>
        <div class=\"col-sm-1\">
          <button type=\"button\" class=\"form-control btn btn-default\" ng-click=\"" . $field->getName() . "_picker = true\"><i class=\"glyphicon glyphicon-calendar\"></i></button>
        </div>
        <div class=\"col-sm-6 text-danger\" ng-show=\"" . $this->getEntity()->getName() . "." . $field->getName() . ".error\">{{" . $this->getEntity()->getName() . "." . $field->getName() . ".error}}</div>
      </div>

";
  }
  
  
  protected function select(Field $field){
    $this->string .= "      <div ng-if=\"sync('" . $field->getName() . "')\" class=\"form-group\">
        <label class=\"control-label col-sm-2\">" . $field->getName("Xx Yy") . ":</label>
        <div class=\"col-sm-4\">
          <select class=\"form-control\"ng-model=\"" . $this->getEntity()->getName() . "." . $field->getName() . ".value\" ng-options=\"opt.id as opt.label for opt in options." . $field->getEntityRef()->getName() . "\" ng-blur=\"check('" . $field->getName() . "')\" ng-change=\"check('" . $field->getName() . "')\">
            <option value=\"\">--" . $field->getName("Xx Yy") . "--</option>
          </select>
        </div>
        <div class=\"col-sm-6 text-danger\" ng-show=\"" . $this->getEntity()->getName() . "." . $field->getName() . ".error\">{{" . $this->getEntity()->getName() . "." . $field->getName() . ".error}}</div>
      </div>

";
  }
  
  protected function selectValues(Field $field){
    $this->string .= "      <div class=\"form-group\">
        <label class=\"control-label col-sm-2\">" . $field->getName("Xx Yy") . ":</label>
        <div class=\"col-sm-4\">
          <select class=\"form-control\" ng-model=\"" . $this->getEntity()->getName() . "." . $field->getName() . ".value\" ng-blur=\"check('" . $field->getName() . "')\" ng-change=\"check('" . $field->getName() . "')\">
            <option value=\"\">--" . $field->getName("Xx Yy") . "--</option>
" ; 
    
    foreach($field->getSelectValues() as $value) $this->string .= "            <option value=\"" . $value . "\">" . $value . "</option>
";
            
    $this->string .= "          </select>
        </div>
        <div class=\"col-sm-6 text-danger\" ng-show=\"" . $this->getEntity()->getName() . "." . $field->getName() . ".error\">{{" . $this->getEntity()->getName() . "." . $field->getName() . ".error}}</div>
      </div>

";

  }
  
 
  
  
  protected function file(Field $field){
    $this->string .= "      <div class=\"form-group\">
        <label class=\"control-label col-sm-2\">" . $field->getName("Xx Yy") . ":</label>
        <div class=\"col-sm-4\">
          <input class=\"form-control\" type=\"file\" ngf-select ng-model=\"" . $this->getEntity()->getName() . "." . $field->getName() . "_file\" ng-blur=\"check('" . $field->getName() . "')\"/>
          <a ng-href=\"{{" . $this->getEntity()->getName() . "." . $field->getName() . "_.contenido}}\" ng-show=\"" . $this->getEntity()->getName() . "." . $field->getName() . "_.contenido\" target=\"_blank\">{{" . $this->getEntity()->getName() . "." . $field->getName() . "_.nombre}}</a>
        </div>              
        <div class=\"col-sm-6 text-danger\" ng-show=\"" . $this->getEntity()->getName() . "." . $field->getName() . ".error\">{{" . $this->getEntity()->getName() . "." . $field->getName() . ".error}}</div>
      </div>

";
  }
  
  
  protected function fileImage(Field $field){
    $this->string .= "      <div class=\"form-group\">
        <label class=\"control-label col-sm-2\">" . $field->getName("Xx Yy") . ":</label>
        <div class=\"col-sm-4\">
          <input class=\"form-control\" type=\"file\" ngf-select ng-model=\"" . $this->getEntity()->getName() . "." . $field->getName() . "_file\" ng-blur=\"check('" . $field->getName() . "')\"/>
          <a ng-href=\"{{" . $this->getEntity()->getName() . "." . $field->getName() . "_.contenido}}\" ng-show=\"" . $this->getEntity()->getName() . "." . $field->getName() . "_.contenido\" target=\"_blank\"><img class=\"img-small\" ng-src=\"{{" . $this->getEntity()->getName() . "." . $field->getName() . "_.contenido}}\" alt=\"{{" . $this->getEntity()->getName() . "." . $field->getName() . "_.nombre}}\" title=\"{{" . $this->getEntity()->getName() . "." . $field->getName() . "_.nombre}}\"/></a>            
        </div>              
        <div class=\"col-sm-6 text-danger\" ng-show=\"" . $this->getEntity()->getName() . "." . $field->getName() . ".error\">{{" . $this->getEntity()->getName() . "." . $field->getName() . ".error}}</div>        
      </div>

";

  }
  
  
  protected function show(Field $field){  
      $this->string .= "      <div class=\"form-group\">
        <label class=\"control-label col-sm-2\">" . $field->getName("Xx Yy") . ":</label>
        <div class=\"col-sm-4\">{{" . $this->getEntity()->getName() . "." . $field->getName() . "}}</div>
        <div class=\"col-sm-6 text-danger\" ng-show=\"" . $this->getEntity()->getName() . "." . $field->getName() . ".error\">{{" . $this->getEntity()->getName() . "." . $field->getName() . ".error}}</div>
      </div>

";
  }
  
  
  protected function textarea(Field $field){
    $this->string .= "      <div class=\"form-group\">
        <label class=\"control-label col-sm-2\">" . $field->getName("Xx Yy") . ":</label>
        <div class=\"col-sm-4\">
          <textarea class=\"form-control\" ng-model=\"" . $this->getEntity()->getName() . "." . $field->getName() . ".value\" ng-blur=\"check('" . $field->getName() . "')\"></textarea>
        </div>
        <div class=\"col-sm-6 text-danger\" ng-show=\"" . $this->getEntity()->getName() . "." . $field->getName() . ".error\">{{" . $this->getEntity()->getName() . "." . $field->getName() . ".error}}</div>
      </div>

";

  }
  
  protected function timestamp(Field $field){
    $this->string .= "      <div class=\"form-group\">
        <label class=\"control-label col-sm-2\">" . $field->getName("Xx Yy") . ":</label>
        <div class=\"col-sm-3\">
          <input class=\"form-control\" placeholder=\"dd/mm/aaaa\" type=\"text\" uib-datepicker-popup=\"dd/MM/yyyy\" uib-datepicker-options=\"datePickerOptions\" ng-model=\"" . $this->getEntity()->getName() . "." . $field->getName() . ".date\" is-open=\"" . $this->getEntity()->getName() . "." . $field->getName() . "_picker\"/>
        </div>
        <div class=\"col-sm-1\">
          <button type=\"button\" class=\"form-control btn btn-default\" ng-click=\"" . $this->getEntity()->getName() . "." . $field->getName() . "_picker = true\"><i class=\"glyphicon glyphicon-calendar\"></i></button>
        </div>
      </div>
      <div class=\"form-group\">
        <div class=\"col-sm-offset-2 col-sm-4\">
          <input class=\"form-control\" type=\"text\" ng-model=\"" . $this->getEntity()->getName() . "." . $field->getName() . ".time\" ng-blur=\"check('" . $field->getName() . "')\" placeholder=\"HH:MM\">
        </div>
        <div class=\"col-sm-6 text-danger\" ng-show=\"" . $this->getEntity()->getName() . "." . $field->getName() . ".error\">{{" . $this->getEntity()->getName() . "." . $field->getName() . ".error}}</div>
      </div>
      
";
    
  }
  
  protected function time(Field $field){
    $this->string .= "      <div class=\"form-group\">
        <label class=\"control-label col-sm-2\">" . $field->getName("Xx Yy") . ":</label>
        <div class=\"col-sm-4\">
          <input class=\"form-control\" type=\"text\" ng-model=\"" . $this->getEntity()->getName() . "." . $field->getName() . ".value_\" ng-blur=\"check('" . $field->getName() . "')\" placeholder=\"HH:MM\">
        </div>
        <div class=\"col-sm-6 text-danger\" ng-show=\"" . $this->getEntity()->getName() . "." . $field->getName() . ".error\">{{" . $this->getEntity()->getName() . "." . $field->getName() . ".error}}</div>
      </div>

";
  }
  
  
  protected function typeahead(Field $field){    
    $this->string .= "      <div ng-if=\"sync('" . $field->getName() . "')\" class=\"form-group\">
        <label class=\"control-label col-sm-2\">" . $field->getName("Xx Yy") . ":</label>      
        <div class=\"col-sm-4\">
          <input type=\"text\" ng-model=\"" . $this->getEntity()->getName() . "." . $field->getName() . ".selected\" placeholder=\"" . $field->getName("Xx Yy") . "\" uib-typeahead=\"opt as opt.label for opt in typeaheadOptions(\$viewValue, '" . $field->getEntityRef()->getName() . "', '" . $field->getName() . "')\" typeahead-wait-ms=\"300\" class=\"form-control\" ng-blur=\"check('" . $field->getName() . "')\">
          <span ng-show=\"selectTypeahead('" . $field->getName() . "')\" class=\"glyphicon glyphicon-ok\"></span>
          <span ng-click=\"modalField('" . $field->getName() . "', '" . $field->getEntityRef()->getName() . "')\" class=\"glyphicon glyphicon-plus\"></span>
        </div>
        <div class=\"col-sm-6 text-danger\" ng-show=\"" . $this->getEntity()->getName() . "." . $field->getName() . ".error\">{{" . $this->getEntity()->getName() . "." . $field->getName() . ".error}}</div>
      </div>

";

  }
  
  //***** generacion de cualquier tipo pasado como parametro *****
  protected function type(Field $field, $type){
    //abrir contenedor principal
    $this->string .= "      <div class=\"form-group\">
        <label class=\"control-label col-sm-2\">" . $field->getName("Xx Yy") . ":</label>
        <div class=\"col-sm-4\">
          <input class=\"form-control\" type=\"" . $type . "\" ng-model=\"" . $this->getEntity()->getName() . "." . $field->getName() . ".value\" ng-blur=\"check('" . $field->getName() . "')\"/>
        </div>
        <div class=\"col-sm-6 text-danger\" ng-show=\"" . $this->getEntity()->getName() . "." . $field->getName() . ".error\">{{" . $this->getEntity()->getName() . "." . $field->getName() . ".error}}</div>
      </div>

";
  
  }
  
  protected function hidden(Field $field) {
    $this->string .= "      <input type=\"hidden\" ng-model=\"" . $this->getEntity()->getName() . "." . $field->getName() . "\" />
";
  }
  
  //***** campos nf *****
  public function nf(){
    $fields = $this->getEntity()->getFieldsNf();
        
    foreach($fields as $field){ if($field->getSubtype() == "show") $this->show($field); }
    foreach($fields as $field){ switch($field->getSubtype()){ case "text": case "email": case "name": case "integer": case "float": case "cuil": case "dni": $this->type($field, "text"); }}
    foreach($fields as $field){ if($field->getSubtype() == "password") $this->type($field, "password"); }
    foreach($fields as $field){ if($field->getSubtype() == "date") $this->date($field); }
    foreach($fields as $field){ if($field->getSubtype() == "timestamp"){ $this->timestamp($field); } }
    foreach($fields as $field){ if($field->getSubtype() == "time"){ $this->time($field); } }    
    foreach($fields as $field){ if($field->getSubtype() == "textarea") $this->textarea($field); }
    foreach($fields as $field){ if((($field->getSubtype() == "select_text") || ($field->getSubtype() == "select_int"))) $this->selectValues($field); }
    foreach($fields as $field){ if($field->getSubtype() == "checkbox") $this->type($field, "checkbox"); }
    foreach($fields as $field){ if($field->getSubtype() == "hidden") $this->hidden($field); }

  }
  
  //***** campos fk *****
  public function fk(){
     $fields = $this->getEntity()->getFieldsFk();
    
    foreach($fields as $field){ if($field->getSubtype() == "select") $this->select($field); }
    foreach($fields as $field){ if($field->getSubtype() == "file") $this->file($field); }
    foreach($fields as $field){ if($field->getSubtype() == "file_image") $this->fileImage($field); }
    foreach($fields as $field){ if($field->getSubtype() == "typeahead") $this->typeahead($field); }
    
  }
  
  
  protected function generateCode(){
    $this->start();
    $this->nf();
    $this->fk();
    $this->end();
  }
  

}
