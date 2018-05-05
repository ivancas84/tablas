import { OnInit } from '@angular/core';
import { FormBuilder, FormGroup } from '@angular/forms';
import { ActivatedRoute } from '@angular/router';

import { Observable } from 'rxjs/Observable';
import { of } from 'rxjs/observable/of';

import 'rxjs/add/operator/map';
import 'rxjs/add/operator/mergeMap';
import 'rxjs/add/observable/forkJoin';


import { DataDefinitionService } from '../../../service/data-definition/data-definition.service';

export class AdminComponent implements OnInit {

  id: string | number = null; //identificador principal
  entity: string; //entidad principal
  data: any = null; //datos: por defecto se procesa la entidad actual, si el formulario esta compuesto por mas de una entidad se redefine en la subclase
  sync: any = null; //sync: { [index: string]: boolean } = {}; datos de sincronizacion, dependiendo de los datos que se manipulen en el formulario variara su tipo entre objeto y array
  disabled: boolean = true; //deshabilitar formulario
  params: any = {};

  adminForm: FormGroup; //formulario

  constructor(protected fb: FormBuilder, protected route: ActivatedRoute, protected dd: DataDefinitionService)  {
    //se crea un formulario vacio, se asignaran subformularios en subcomponentes mediante el metodo FormGroup.addControl
    this.adminForm = this.fb.group({});
    this.adminForm.disable();
    this.queryParams();
  }


  /*
  //getData realiza una consulta a los datos de una entidad
  //si se desea construir un formulario mas avanzado, sobrescribir el metodo en la subclase

  queryTransaction() {
    this.dd.checkTransaction().subscribe(
      response => { this.getData(); }
    )
  }

  */

  queryParams() {
    return this.route.queryParams.subscribe(
      params => {
        for(let i in params){
          if(params.hasOwnProperty(i)) this.params[i] = params[i];
          if("id" in params) this.id = params["id"];
        }
      }
    );
  }

  onSubmit() {
    this.adminForm.disable();
    let serverData: any[] = [];
    var row = this.dd.server(this.entity, this.adminForm.value[this.entity]);
    serverData.push({entity:this.entity, row:row});
    this.dd.process(serverData).subscribe(
      response => {
        console.log(response)
        this.id = response[0]["id"];
      }
    );
  }

  getData() {
    this.dd.getOrNull(this.entity, this.id).subscribe(
      row => {
        this.adminForm.enable();
        this.data = row;
      },
      error => { console.log(error); }
    );
  }

  reset(){ this.getData() }

  ngOnInit(){
    this.dd.checkTransaction().subscribe(
      response => { this.getData(); }
    )
  }




}
