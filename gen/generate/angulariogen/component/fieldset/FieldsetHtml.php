<?php

require_once("generate/GenerateFileEntity.php");

class ComponentFieldsetHtml extends GenerateFileEntity {

  public function __construct(Entity $entity, $directorio = null) {
    $file = $entity->getName("xx-yy") . "-fieldset.component.html";
    if(!$directorio) $directorio = PATH_GEN . "tmp/component/fieldset/" . $entity->getName("xx-yy") . "-fieldset/";
    parent::__construct($directorio, $file, $entity);
  }


  public function generateCode() {
    $this->start();
    $this->nf();
    $this->fk();
    $this->end();
  }

  protected function start() {
    $this->string .= "<fieldset *ngIf=\"fieldsetForm && enable\" [formGroup]=\"fieldsetForm\">
";
  }


  protected function nf() {
    $fields = $this->getEntity()->getFieldsNf();

    foreach($fields as $field) {
      if(!$field->isAdmin()) continue;
      switch ( $field->getSubtype() ) {
        case "checkbox": $this->checkbox($field); break;
        case "date": $this->date($field);  break;
        //case "float": case "integer": case "cuil": case "dni": $this->number($field); break;
        // case "year": $this->date($field); break;
        case "timestamp":
          //la administracion de timestamp se encuentra deshabilitada debido a que requiere de formato adicional
          //$this->timestamp($field);
        break;
        // case "time": $this->time($field); break;
        case "select_text": $this->selectValues($field); break;
        case "select_int": $this->selectValues($field); break;
        default: $this->defecto($field); //name, email
      }
    }
  }


  public function fk(){
    $fields = $this->getEntity()->getFieldsFk();

    foreach($fields as $field){
      if(!$field->isAdmin()) continue;
      switch($field->getSubtype()) {
        case "select": $this->select($field); break;
        case "typeahead": $this->typeahead($field); break;
      }
    }
  }




  protected function date(Field $field) {
    $this->string .= "  <div class=\"form-group form-row\">
    <label class=\"col-sm-2 col-form-label\">{$field->getName('Xx yy')}</label>
    <div class=\"col-sm-10\">
      <div class=\"input-group\">
        <input class=\"form-control\" placeholder=\"yyyy-mm-dd\" ngbDatepicker #" . $field->getName("xxYy") . "=\"ngbDatepicker\" formControlName=\"{$field->getName()}\"  [ngClass]=\"{'is-invalid':(fieldsetForm.get('" . $field->getName() . "').invalid && (fieldsetForm.get('" . $field->getName() . "').dirty || fieldsetForm.get('" . $field->getName() . "').touched))}\">
        <div class=\"input-group-append\">
          <button class=\"btn btn-outline-secondary\" (click)=\"" . $field->getName("xxYy") . ".toggle()\" type=\"button\">
            <span class=\"oi oi-calendar\"></span>
          </button>
        </div>
      </div>
";
      $this->templateError($field);
      $this->string .= "    </div>
  </div>
";
  }

  protected function timestamp(Field $field) {
    $this->string .= "  <div class=\"form-group form-row\">
    <label class=\"col-sm-2 col-form-label\">{$field->getName('Xx yy')}</label>
    <div class=\"col-sm-10\">
      <div class=\"input-group\" formGroupName=\"{$field->getName()}\">
        <input class=\"form-control\" placeholder=\"yyyy-mm-dd\" ngbDatepicker #" . $field->getName("xxYy") . "Date=\"ngbDatepicker\" formControlName=\"date\"  [ngClass]=\"{'is-invalid':(fieldsetForm.get('" . $field->getName() . ".date').invalid && (fieldsetForm.get('" . $field->getName() . ".date').dirty || fieldsetForm.get('" . $field->getName() . ".date').touched))}\">
        <div class=\"input-group-append\">
          <button class=\"btn btn-outline-secondary\" (click)=\"" . $field->getName("xxYy") . "Date.toggle()\" type=\"button\">
            <span class=\"oi oi-calendar\"></span>
          </button>
        </div>
        <ngb-timepicker formControlName=\"time\"></ngb-timepicker>
      </div>
";
      //$this->templateError($field);
      $this->string .= "    </div>
  </div>
";
  }




  protected function defecto(Field $field) {

    $this->string .= "  <div class=\"form-group form-row\">
    <label class=\"col-sm-2 col-form-label\">" . $field->getName("Xx yy") . "</label>
    <div class=\"col-sm-10\">
      <input class=\"form-control\" type=\"text\" formControlName=\"" . $field->getName() . "\"  [ngClass]=\"{'is-invalid':(fieldsetForm.get('" . $field->getName() . "').invalid && (fieldsetForm.get('" . $field->getName() . "').dirty || fieldsetForm.get('" . $field->getName() . "').touched))}\">
";
    $this->templateError($field);
    $this->string .= "    </div>
  </div>
";
  }


  protected function checkbox(Field $field) {
    $this->string .= "  <div class=\"form-group form-check\">
    <label class=\"form-check-label\">
      <input class=\"form-check-input\" type=\"checkbox\" formControlName=\"{$field->getName()}\"> {$field->getName()}
    </label>
";
    $this->templateError($field);
    $this->string .= "  </div>
";
  }

  protected function selectValues(Field $field){
    $this->string .= "  <div class=\"form-group form-row\">
    <label class=\"col-sm-2 col-form-label\">" . $field->getName("Xx Yy") . ":</label>
    <div class=\"col-sm-10\">
      <select class=\"form-control\" formControlName=\"" . $field->getName() . "\" [ngClass]=\"{'is-invalid':({$field->getName()}.invalid && ({$field->getName()}.dirty || {$field->getName()}.touched))}\">
        <option [ngValue]=\"null\">--" . $field->getName("Xx Yy") . "--</option>
" ;

    foreach($field->getSelectValues() as $value) $this->string .= "            <option value=\"" . $value . "\">" . $value . "</option>
";

    $this->string .= "      </select>
";
    $this->templateError($field);
    $this->string .= "    </div>
  </div>
";

  }


  protected function select(Field $field) {
    $this->string .= "  <div *ngIf=\"isSync('" . $field->getName() . "')\" class=\"form-group form-row\">
    <label class=\"col-sm-2 col-form-label\">" . $field->getName("Xx Yy") . "</label>
    <div class=\"col-sm-10\">
      <select class=\"form-control\" formControlName=\"" . $field->getName() . "\" [ngClass]=\"{'is-invalid':({$field->getName()}.invalid && ({$field->getName()}.dirty || {$field->getName()}.touched))}\">
        <option [ngValue]=\"null\">--" . $field->getName("Xx Yy") . "--</option>
        <option *ngFor=\"let option of options." . $field->getEntityRef()->getName() . "\" [value]=\"option.id\" >{{option.id | label:\"{$field->getEntityRef()->getName()}\"}}</option>
      </select>
";
    $this->templateError($field);
    $this->string .= "    </div>
  </div>
";
  }

  protected function typeahead(Field $field) {
    $this->string .= "  <div *ngIf=\"isSync('" . $field->getName() . "')\" class=\"form-group row\">
    <label class=\"col-sm-2 col-form-label\">" . $field->getName("Xx Yy") . "</label>
    <div class=\"col-sm-10\">
      <app-fieldset-typeahead [fieldset]=\"fieldsetForm\" [entityName]=\"'" . $field->getEntityRef()->getName() . "'\" [fieldName]=\"'" . $field->getName() . "'\"></app-fieldset-typeahead>
";
      $this->templateError($field);
      $this->string .= "    </div>
  </div>
";
  }



  protected function end() {
    $this->string .= "</fieldset>
";
  }




  protected function templateError(Field $field){
    $this->string .= "      <div class=\"text-danger\" *ngIf=\"({$field->getName("xxYy")}.touched || {$field->getName("xxYy")}.dirty) && {$field->getName("xxYy")}.invalid\">
";
    if($field->isNotNull()) $this->string .= "        <div *ngIf=\"{$field->getName("xxYy")}.errors.required\">Debe completar valor</div>
";
    if($field->isUnique()) $this->string .= "        <div *ngIf=\"{$field->getName("xxYy")}.errors.notUnique\">El valor ya se encuentra utilizado: <a routerLink=\"/{$field->getEntity()->getName("xx-yy")}-admin\" [queryParams]=\"{'{$field->getName()}':{$field->getName('xxYy')}.value}\">Cargar</a></div>
";
    switch($field->getSubtype()) {
      case "email": $this->templateErrorEmail($field); break;
      case "dni": $this->templateErrorDni($field); break;
    }
    $this->string .= "      </div>
";
  }

  protected function templateErrorEmail(Field $field) {
    $this->string .= "        <div *ngIf=\"{$field->getName("xxYy")}.errors.email\">Debe ser un email válido</div>
";
  }

  protected function templateErrorDni(Field $field) {
    $this->string .= "        <div *ngIf=\"{$field->getName("xxYy")}.errors.pattern\">Ingrese solo números</div>
        <div *ngIf=\"{$field->getName("xxYy")}.errors.minlength\">Longitud incorrecta</div>
        <div *ngIf=\"{$field->getName("xxYy")}.errors.maxlength\">Longitud incorrecta</div>
";
  }

}
