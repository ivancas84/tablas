import { OnInit, Input, OnChanges, SimpleChanges} from '@angular/core';
import { ActivatedRoute } from '@angular/router';

import { Field } from '../../class/field';
import { Display } from '../../class/display';

import { DataDefinitionService } from '../../../service/data-definition/data-definition.service';

export class ShowComponent implements OnInit {

  @Input() display: Display = new Display(); //parametros de consulta en un formato entendible por el servidor
  entity: string; //entidad principal
  rows: any = []; //datos a visualizar
  sync: { [index: string]: boolean } = {}; //datos de sincronizacion
  disabled: boolean; //deshabilitar inputs (si existen)

  constructor(protected dd: DataDefinitionService, protected route: ActivatedRoute) { }

  queryParams() {
    this.route.queryParams.subscribe(
      params => {
        if ("sid" in params) {
           this.display = JSON.parse(decodeURI(params["sid"]));
        } else {
          for(let i in params){
            if(params.hasOwnProperty(i)){
              if(!(i in this.display)) this.display.filters.push({field:i, option:"=", value:params[i]}); //asignar filtro
              else this.display[i] = params[i]; //asignar parametro
            }
          }
        }
      }
    );
  }

  getData() {
    this.dd.all(this.entity, this.display).subscribe(
      rows => {
        for (let i = 0; i < rows.length; i++) {
          this.dd.init(this.entity, rows[i]).subscribe(
            row => {
               this.rows.push(row);
             }
          );
        }
      },
      error => { console.log(error); }
    );
  }

  ngOnInit() {
    this.queryParams();
    this.getData();
  }


}
