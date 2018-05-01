<?php

require_once("generate/GenerateFileEntity.php");


class Interface extends GenerateFileEntity {

  public function __construct(Entity $entity, $directorio = null){
    $file = $entity->getName("xx-yy") . ".ts";
    if(!$directorio) $directorio = PATH_ROOT . "src/app/class/";
    parent::__construct($directorio, $file, $entity);
  }


  public function generateCode() {
    $this->start();
    $this->nf();
    $this->fk();    
    $this->end();
  }


  protected function start(){
    $this->string .= "interface " . $this->entity->getName("XxYy") . " {
";
  }



  protected function nf(){
    $fields = $this->getEntity()->getFieldsNf();

    foreach ($fields as $field) {
      $this->string .= "  " . $this->getName("xxYy") . "? : " . $field->getTypescriptType() . ",
";

    }
  }

  protected function fk(){
    $fields = $this->getEntity()->getFieldsFk();

    foreach ($fields as $field) {
      $this->string .= "  " . $this->getName("xxYy") . "_? : string
";

    }
  }



  protected function end(){
    $this->string .= "          </tr>
        </tbody>
      </table>
    </div>
  </div>
";
  }













  protected function optionsRef(){
    $ref = $this->getEntity()->getFieldsRef();

    foreach($ref as $field){
      $this->string .= "          <a class=\"btn btn-info btn-xs\" ng-href=\"#!grid" . $field->getEntity()->getName("XxYy") . "?" . $field->getName() . "={{row." . $this->getEntity()->getPk()->getName() . ".value}}\">" . $field->getEntity()->getName("Xx Yy") . "</a>
";
    }


  }

  protected function options(){
    $this->string .= "            <td>
              <button class=\"btn btn-warning btn-xs\" ng-click=\"modalModify(\$index)\"><span class=\"glyphicon glyphicon-pencil\"></span></button>
              <button class=\"btn btn-danger btn-xs\" type=\"button\" ng-click=\"delete_(\$index)\"><span class=\"glyphicon glyphicon-remove\"></span></button>
";

    $this->optionsRef();

    $this->string .= "            </td>
";
  }







  protected function defecto(Field $field){
    $this->string .= "<div>{{row." . $field->getName() . ".value}}</div>";
  }



  protected function textarea(Field $field){
    $this->string .= "<span title=\"{{row." . $field->getName() . ".value}}\">{{row." . $field->getName() . ".summary}}</span>";
  }

  protected function date(Field $field){
    $this->string .= "{{row." . $field->getName() . ".value | date:'dd/MM/yyyy'}}";
  }

  protected function checkbox(Field $field){
    $this->string .= "<div>{{row." . $field->getName() . ".value ? 'Sí' : 'No'}}</div>";
  }


  protected function time(Field $field){
    $this->string .= "<div>{{row." . $field->getName() . ".value_}}</div>";
  }

  protected function timestamp(Field $field){
    $this->string .= "{{row." . $field->getName() . ".date | date:'dd/MM/yyyy'}} {{row." . $field->getName() . ".time}}";
  }



}
