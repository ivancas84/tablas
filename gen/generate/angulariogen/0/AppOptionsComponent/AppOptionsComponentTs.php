<?php


class AppOptionsComponentTs extends GenerateFile {

  public function __construct(array $structure) {
    $dir = PATH_GEN . "src/app/options/";
    $file = "options.component.ts";
    parent::__construct($dir, $file);
  }


  protected function start(){
    $this->string .= "import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-options',
  templateUrl: './options.component.html',
})
export class OptionsComponent implements OnInit {

  constructor() { }

  ngOnInit() {
  }

}
";
  }



  //***** @override *****
  protected function generateCode() {
    $this->start();
  }

}
