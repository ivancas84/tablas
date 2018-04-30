import { Input } from '@angular/core';


export abstract class TableComponent  {
  @Input() rows: any = []; //datos a visualizar
  @Input() sync: any = []; //configuracion de sincronizacion

  isSync(){
    console.log("en construccion");
  }

  order(){
    console.log("en construccion");
  }
}
