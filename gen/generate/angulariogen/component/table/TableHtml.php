<?php

require_once("generate/GenerateFileEntity.php");


class ComponentTableHtml extends GenerateFileEntity {

  public function __construct(Entity $entity, $directorio = null){
    $file = $entity->getName("xx-yy") . "-table.component.html";
    if(!$directorio) $directorio = PATH_GEN . "tmp/component/table/" . $entity->getName("xx-yy") . "-table/";
    parent::__construct($directorio, $file, $entity);
  }


  public function generateCode() {
    $this->start();
    $this->headersNf();
    $this->headersFk();
    $this->headersOptions();
    $this->body();
    $this->valuesNf();
    $this->valuesFk();
    $this->options();
    $this->end();
  }


  protected function start(){
    $this->string .= "
    <div [hidden]=\"!rows.length\" class=\"table-responsive\">
      <table class=\"table table-striped table-bordered table-hover\">
        <thead>
          <tr>
";
  }



  protected function headersNf(){
    $fields = $this->getEntity()->getFieldsNf();

    foreach ($fields as $field) {
      if($field->isHidden()) continue; //se omiten los campos de agregacion
      $name = $field->getName("Xx Yy");
      $sort = $field->getName();

      $this->string .= "            <th><button type=\"button\" class=\"btn btn-link text-dark font-weight-bold\" (click)=\"setOrder('{$sort}')\">{$name}</button></th>
" ;

    }
  }

  protected function headersFk(){
    foreach ($this->getEntity()->getFieldsFk() as $field) {

      $name = $field->getName("Xx Yy");

      $fieldsFk = $field->getEntityRef()->getFields();
      $fieldsFkMain = array();
      foreach($fieldsFk as $fieldFk){
        if($fieldFk->isMain()){
          array_push($fieldsFkMain, $field->getAlias() . "_" . $fieldFk->getName());
        }
      }

     $this->string .= "            <th *ngIf=\"isSync('" . $field->getName() . "')\"><button type=\"button\" class=\"btn btn-link text-dark font-weight-bold\" (click)=\"setOrder('" . implode("', '", $fieldsFkMain) . "')\">{$name}</button></th>
" ;

    }
  }

  protected function headersOptions(){
    $this->string .= "            <th>Opciones</th>
  " ;
  }

  protected function body(){
    $this->string .= "          </tr>
        </thead>
        <tbody>
          <tr *ngFor=\"let row of rows; let i = index\">
  ";
  }


    protected function valuesNf(){


      foreach ($this->getEntity()->getFieldsNf() as $field) {
        if($field->isHidden()) continue; //se omiten los campos de agregacion
        $this->string .= "            <td>" ;
        switch($field->getSubtype()){
          //case "checkbox": $this->checkbox($field); break;
          case "date": $this->date($field); break;
          //case "timestamp": $this->timestamp($field); break;
          //case "time": $this->time($field); break;
          default: $this->defecto($field); break;
        }
        $this->string .= "</td>
  " ;
      }
    }



    protected function valuesFk(){
      foreach($this->getEntity()->getFieldsFk() as $field){
        $this->string .= "            <td *ngIf=\"isSync('" . $field->getName() . "')\">" ;
        switch($field->getSubtype()){
          default: $this->string .= "<a [routerLink]=\"['/" . $field->getEntityRef()->getName("xx-yy") . "-show']\" [queryParams]=\"{id:row." . $field->getName() . "}\" >{{row." . $field->getName() . " | label:'{$field->getEntityRef()->getName()}'}}</a>" ;
        }
        $this->string .= "</td>
  " ;
      }
    }


  protected function end(){
    $this->string .= "          </tr>
        </tbody>
      </table>
    </div>
";
  }













  protected function optionsRef(){
    foreach($this->getEntity()->getFieldsRef() as $field){
      $this->string .= "              <a class=\"btn btn-info btn-sm\" [routerLink]=\"['/" . $field->getEntity()->getName("xx-yy") . "-show']\" [queryParams]=\"{" . $field->getName() . ":row.id}\">" . $field->getEntity()->getName("Xx Yy") . "</a>
";
    }


  }

  protected function options(){
    $this->string .= "            <td>
              <a class=\"btn btn-warning btn-sm\" [routerLink]=\"['/" . $this->getEntity()->getName("xx-yy") . "-admin']\" [queryParams]=\"{id:row.id}\" ><span class=\"oi oi-pencil\" title=\"Modificar\"></span></a>
              <button class=\"btn btn-danger btn-sm\" type=\"button\" (click)=\"delete(i)\"><span class=\"oi oi-trash\" title=\"Eliminar\"></span></button>
";

    //$this->optionsRef();

    $this->string .= "            </td>
";
  }







  protected function defecto(Field $field){
    $this->string .= "<div>{{row." . $field->getName() . "}}</div>";
  }



  protected function textarea(Field $field){
    $this->string .= "<span title=\"{{row." . $field->getName() . "}}\">{{row." . $field->getName() . ".summary}}</span>";
  }

  protected function date(Field $field){
    $this->string .= "{{row." . $field->getName() . " | toDate | date:'dd/MM/yyyy'}}";
  }

  protected function checkbox(Field $field){
    $this->string .= "<div>{{row." . $field->getName() . " ? 'Sí' : 'No'}}</div>";
  }


  protected function time(Field $field){
    $this->string .= "<div>{{row." . $field->getName() . "}}</div>";
  }

  protected function timestamp(Field $field){
    $this->string .= "{{row." . $field->getName() . ".date | date:'dd/MM/yyyy'}} {{row." . $field->getName() . ".time}}";
  }



}
