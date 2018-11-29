<?php

require_once("generate/GenerateFile.php");

class AppRoutingModuleTs extends GenerateFile {

  protected $structure;

  public function __construct(array $structure) {
    $directorio = PATH_GEN . "/src/app/";
    $nombreArchivo = "app-routing.module.ts";
    parent::__construct($directorio, $nombreArchivo);
    $this->structure = $structure;
  }


  public function start(){
    $this->string .= "
import { NgModule }             from '@angular/core';
import { RouterModule, Routes } from '@angular/router';

import { OptionsComponent } from './options/options.component'

";

  }

  public function end(){
    $this->string .= "];

@NgModule({
  imports: [ RouterModule.forRoot(routes) ],
  exports: [ RouterModule ]
})
export class AppRoutingModule {}
";

  }


  public function imports(){
    foreach($this->structure as $entity){
      $this->string .= "import { " . $entity->getName("XxYy") . "GridComponent } from './" . $entity->getName("xx-yy") . "-grid/" . $entity->getName("xx-yy") . "-grid.component'
";

    }
  }

  public function initRoutes(){
    $this->string .= "
const routes: Routes = [
  { path: '', redirectTo: '/options', pathMatch: 'full' },
  { path: 'options', component: OptionsComponent },

";

  }

  public function routes(){
    foreach($this->structure as $entity){
      $this->string .= "  { path: '" . $entity->getName("xx-yy") . "-grid', component: " . $entity->getName("XxYy") . "GridComponent },
";

    }
  }

  protected function generateCode() {
    $this->start();
    $this->imports();
    $this->initRoutes();
    $this->routes();
    $this->end();
    return $this->string;
  }

}
