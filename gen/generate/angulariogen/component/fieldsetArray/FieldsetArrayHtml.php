<?php

require_once("generate/GenerateFileEntity.php");

class ComponentFieldsetArrayHtml extends GenerateFileEntity {

  public function __construct(Entity $entity, $directorio = null) {
    $file = $entity->getName("xx-yy") . "-fieldset-array.component.html";
    if(!$directorio) $directorio = PATH_ROOT . "tmp/component/fieldset-array/" . $entity->getName("xx-yy") . "-fieldset-array/";
    parent::__construct($directorio, $file, $entity);
  }


  public function generateCode() {
    $this->start();
    $this->nf();
    $this->fk();
    $this->end();
  }

  protected function start() {
    $this->string .= "<fieldset *ngFor=\"let form of fieldsetForm.controls; let i=index\">
    <div [formGroup]=\"form\">
";
  }


  protected function nf() {
    $fields = $this->getEntity()->getFieldsNf();

    foreach($fields as $field) {
      switch ( $field->getSubtype() ) {
        case "checkbox": $this->checkbox($field); break;
        //case "date": $this->date($field);  break;
        //case "float": case "integer": case "cuil": case "dni": $this->number($field); break;
        // case "year": $this->date($field); break;
        // case "timestamp": $this->timestamp($field); break;
        // case "time": $this->time($field); break;
        case "select_text": $this->selectValues($field); break;
        case "select_int": $this->selectValues($field); break;
        default: $this->defecto($field); //name, email
      }
    }
  }


  public function fk(){
     $fields = $this->getEntity()->getFieldsFk();

    foreach($fields as $field){ if($field->getSubtype() == "select") $this->select($field); }
    foreach($fields as $field){ if($field->getSubtype() == "typeahead") $this->typeahead($field); }

  }


  protected function end() {
    $this->string .= "    <button (click)=\"removeRow(i)\" type=\"button\" >Eliminar</button>

  </div>

</fieldset>
<button (click)=\"addRow()\" type=\"button\" >Agregar</button>
";
  }













  protected function defecto(Field $field) {
    $this->string .= "  <div class=\"form-group row\">
    <label class=\"col-2 col-form-label\">" . $field->getName("Xx yy") . "</label>
    <div class=\"col-10\">
      <input class=\"form-control\" type=\"text\" formControlName=\"" . $field->getName() . "\"  [ngClass]=\"{'is-invalid':(form.get('" . $field->getName() . "').invalid && (form.get('" . $field->getName() . "').dirty || form.get('" . $field->getName() . "').touched))}\">
    </div>
  </div>
";
  }


  protected function checkbox(Field $field) {
    $this->string .= "  <div class=\"form-group form-check\">
    <label class=\"form-check-label\">
      <input class=\"form-check-input\" type=\"{$field->getName()}\" formControlName=\"addDomicilio\"> {$field->getName()}
    </label>
  </div>
";
  }

  protected function selectValues(Field $field){
    $this->string .= "      <div class=\"form-group row\">
        <label class=\"col-2 col-form-label\">" . $field->getName("Xx Yy") . ":</label>
        <div class=\"col-10\">
          <select class=\"form-control\" formControlName=\"" . $field->getName() . "\" [ngClass]=\"{'is-invalid':(form.get('" . $field->getName() . "').invalid && (form.get('" . $field->getName() . "').dirty || form.get('" . $field->getName() . "').touched))}\">
            <option value=\"null\">--" . $field->getName("Xx Yy") . "--</option>
" ;

    foreach($field->getSelectValues() as $value) $this->string .= "            <option value=\"" . $value . "\">" . $value . "</option>
";

    $this->string .= "          </select>
        </div>
      </div>

";

  }


  protected function select(Field $field) {
    $this->string .= "  <div *ngIf=\"isSync('" . $field->getName() . "')\" class=\"form-group row\">
    <label class=\"col-2 col-form-label\">" . $field->getName("Xx Yy") . "</label>
    <div class=\"col-10\">
      <select class=\"form-control\" formControlName=\"" . $field->getName() . "\" [ngClass]=\"{'is-invalid':(form.get('" . $field->getName() . "').invalid && (form.get('" . $field->getName() . "').dirty || form.get('" . $field->getName() . "').touched))}\">
        <option value=\"null\">--" . $field->getName("Xx Yy") . "--</option>
        <option *ngFor=\"let option of options." . $field->getEntityRef()->getName() . "\" [value]=\"option.id\" >{{option.label}}</option>
      </select>
    </div>
  </div>
";
  }

  protected function typeahead(Field $field) {

    $this->string .= "  <div *ngIf=\"isSync('" . $field->getName() . "')\" class=\"form-group row\">
    <label class=\"col-2 col-form-label\">" . $field->getName("Xx Yy") . "</label>
    <div class=\"col-10\">
      <app-fieldset-typeahead [entity]=\"'" . $field->getEntityRef()->getName() . "'\" [fieldsetForm]=\"form\" [field]=\"'" . $field->getName() . "'\"></app-fieldset-typeahead>
    </div>
  </div>
";
  }


}
