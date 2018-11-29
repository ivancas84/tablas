<?php

require_once("generate/GenerateFile.php");

class AppModuleTs extends GenerateFile {

  protected $structure;

  public function __construct(array $structure) {
    $directorio = PATH_GEN . "/src/app/";
    $nombreArchivo = "app.module.ts";
    parent::__construct($directorio, $nombreArchivo);
    $this->structure = $structure;
  }


  public function start(){
    $this->string .= "
import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { FormsModule } from '@angular/forms';
import { HttpClientModule } from '@angular/common/http';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';

import { AppComponent } from './app.component';
import { AppRoutingModule } from './app-routing.module';

import { OptionsComponent } from './options/options.component'

";

  }


  public function imports(){
    foreach($this->structure as $entity){
      $this->string .= "import { " . $entity->getName("XxYy") . "GridComponent } from './" . $entity->getName("xx-yy") . "-grid/" . $entity->getName("xx-yy") . "-grid.component'
";

    }
  }


  public function ngModuleStart(){
    $this->string .= "
@NgModule({
  imports: [
    BrowserAnimationsModule,
    BrowserModule,
    FormsModule,
    AppRoutingModule,
    HttpClientModule,
  ],
  declarations: [
    AppComponent,
    OptionsComponent,

";
  }

  protected function ngModuleDeclarations(){
    foreach($this->structure as $entity){
      $this->string .= "    " . $entity->getName("XxYy") . "GridComponent,
";
    }
  }



  public function end(){
    $this->string .= "  ],
  bootstrap: [ AppComponent ]
})
export class AppModule { }
";

  }






  protected function generateCode() {
    $this->start();
    $this->imports();
    $this->ngModuleStart();
    $this->ngModuleDeclarations();
    $this->end();
    return $this->string;
  }

}
