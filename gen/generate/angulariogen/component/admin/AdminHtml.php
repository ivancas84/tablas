<?php

require_once("generate/GenerateFileEntity.php");


class ComponentAdminHtml extends GenerateFileEntity {

  public function __construct(Entity $entity, $directorio = null){
    $file = $entity->getName("xx-yy") . "-admin.component.html";
    if(!$directorio) $directorio = PATH_GEN . "tmp/component/admin/" . $entity->getName("xx-yy") . "-admin/";
    parent::__construct($directorio, $file, $entity);
  }



  public function generateCode() {
    $this->string .= "
<form [formGroup]=\"adminForm\" (ngSubmit)=\"onSubmit()\" novalidate>
  <app-" . $this->getEntity()->getName("xx-yy") . "-fieldset [adminForm]=\"adminForm\" [sync]=\"sync\" [row]=\"data\" (changeFieldset)=\"changeForm(\$event)\"></app-" . $this->getEntity()->getName("xx-yy") . "-fieldset>
  <button [disabled]=\"adminForm.pristine || !adminForm.valid\" type=\"submit\" class=\"btn btn-success\">Aceptar</button>&nbsp;
  <button type=\"button\" (click)=\"reset()\" [disabled]=\"adminForm.pristine\" class=\"btn btn-danger\">Restablecer</button>

  <!--p>Debug Form value: {{ adminForm.value | json }}</p>
  <p>Debug Form status: {{ adminForm.status | json }}</p-->
</form>
";

  }
}
