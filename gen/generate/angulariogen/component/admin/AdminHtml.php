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
  <app-" . $this->getEntity()->getName("xx-yy") . "-fieldset [adminForm]=\"adminForm\" [sync]=\"sync\" [row]=\"data.{$this->entity->getName()}\"></app-" . $this->getEntity()->getName("xx-yy") . "-fieldset>
  <button type=\"submit\" class=\"btn btn-primary\">Aceptar</button>&nbsp;
  <button type=\"button\" class=\"btn btn-secondary\" (click)=\"back()\"><span class=\"oi oi-arrow-thick-left\" title=\"Volver\"></span></button>
  <button type=\"button\" (click)=\"reset()\" class=\"btn btn-secondary\" ><span class=\"oi oi-reload\" title=\"Restablecer\"></span></button>
  <button type=\"button\" (click)=\"initData()\" class=\"btn btn-secondary\" ><span title=\"Nuevo\" class=\"oi oi-plus\"></span></button
  <button type=\"button\" class=\"btn btn-secondary\" [disabled]=\"deleteDisabled\" (click)=\"delete()\"><span class=\"oi oi-x\" title=\"Eliminar\"></span></button>
  


  <!--p>Debug Form value: {{ adminForm.value | json }}</p>
  <p>Debug Form status: {{ adminForm.status | json }}</p-->
</form>
";

  }
}
