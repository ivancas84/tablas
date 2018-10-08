import { HttpClient } from '@angular/common/http';
import { FormBuilder, FormGroup } from '@angular/forms';

import { Observable } from 'rxjs/Observable';
import { of } from 'rxjs/observable/of';
import 'rxjs/add/operator/map';
import 'rxjs/add/operator/mergeMap';
import 'rxjs/add/observable/forkJoin';
import 'rxjs/add/operator/catch';

import { SessionStorageService } from '../storage/session-storage.service';
import { ParserService } from '../parser/parser.service';
import { MessageService } from '../message/message.service';


import { HTTP_OPTIONS, API_ROOT } from '../../../app.config';
import { DataDefinitionLoaderService } from '../../../service/data-definition/data-definition-loader.service';
import { Display } from '../../class/display';
import { DataDefinition } from './data-definition';


export class DataDefinitionMainService {

  constructor(public fb: FormBuilder, public http: HttpClient, public storage: SessionStorageService, public loader: DataDefinitionLoaderService, public parser: ParserService, public message: MessageService) { }

  uniqueId(): string {
    let start = new Date().getTime();
    while (new Date().getTime() < start + 1); //esperar un microsegundo para evitar colisiones en caso de multiples llamados al metodo

    let date = Date.now().toString();
    let number =  (Math.floor(Math.random()*10000)).toString(); //numero aleatorio de 4 posisiones
    if ( (4 - number.length) > 0 ) number = "0" + number;
    return date+number;
  }

  //field sincronizado? Un field se considera "sincronizado" si es estrictamente distinto de false
  isSync(key, sync): boolean { return (!sync || !(key in sync) || sync[key]) ? true : false; }

  options(entity, sync): Observable<any> {
    let ddi: DataDefinition = this.loader.dataDefinition(entity, this);
    return ddi.options(sync);
  }

  all (entity: string, display: Display = null): Observable<any> {
    let key = "_" + entity + "_all" + JSON.stringify(display);
    if(this.storage.keyExists(key)) return of(this.storage.getItem(key));

    let url = API_ROOT + entity + '/all'
    return this.http.post<any>(url, "data="+JSON.stringify(display), HTTP_OPTIONS).map(
      rows => {
        this.storage.setItem(key, rows);

        for(let i = 0; i < rows.length; i++){
          let ddi: DataDefinition = this.loader.dataDefinition(entity, this);
          ddi.storage(rows[i]);
        }

        return rows;
      }
    );
  }




  get (entity: string, id: string|number): Observable<any> {
    if(!id) return Observable.throw("id es nulo");
    return this.getAll(entity, [id]).mergeMap(
      rows => {
        if(rows.length > 1) return Observable.throw("La consulta retorno mas de un registro");
        if(rows.length == 0) return Observable.throw("La consulta no retorno registro");
        return of(rows[0]);
      }
    )
  }

  getOrNull (entity: string, id: string|number): Observable<any>  {
    if(!id) return of(null);

    return this.getAll(entity, [id]).mergeMap(
      rows => {
        if(rows.length > 1) return Observable.throw("La consulta retorno mas de un registro");
        return (rows.length != 1) ? of(null) : of(rows[0]);
      }
    )
  }

  //recibe una lista de ids, y retorna sus datos (deben estar ordenados en el mismo orden que se reciben los ids)
  //simplifica la cantidad de valores retornados utilizando el cache, pero realiza un procesamiento adicional de ordenamiento en el cliente.
  getAll (entity: string, ids: any): Observable<any> {
    let rows: Array<{ [index: string]: boolean|string|number }> = new Array(ids.length);

    let searchIds: Array<string | number> = new Array();

    for(let i = 0; i < ids.length; i++) {
      let data: { [index: string]: boolean|string|number }  = this.storage.getItem(entity + ids[i]);

      rows[i] = data;
      if(!data) searchIds.push(ids[i]); //BUG: SI ES BOOLEAN FALSE?
    }


    if (searchIds.length > 0) {
      //return new Promise((resolve, reject) => {
        let url: string = API_ROOT + entity + '/getAll';
        return this.http.post<any>(url, "ids="+JSON.stringify(searchIds), HTTP_OPTIONS).map(
          rows_ => {

            for(let i = 0; i < rows_.length; i++){
              let ddi: DataDefinition = this.loader.dataDefinition(entity, this);
              ddi.storage(rows_[i]);
              let i_string: string = String(rows_[i].id);
              let i_int: number = parseInt(i_string);
              let j: string | number = ids.indexOf(i_string);
              if(j == -1){ j = ids.indexOf(i_int); } //BUG: chequear por ambos tipos
              rows[j] = rows_[i];
            }

            return rows_;
          }
        );
    } else {
      return of(rows);
    }
  }

  ids (entity: string, display: Display = null): Observable<any> {
    let key = "_" + entity + "_ids" + JSON.stringify(display);
    if(this.storage.keyExists(key)) return of(this.storage.getItem(key));

    let url = API_ROOT + entity + '/ids'
    return this.http.post<any>(url, "data="+JSON.stringify(display), HTTP_OPTIONS).map(
      ids => {
        this.storage.setItem(key, ids);
        return ids;
      }
    );
  }

  idOrNull (entity: string, display: Display = null): Observable<any> {
    return this.ids(entity, display).mergeMap(
      ids => {
        if(ids.length > 1) return Observable.throw("La consulta retorno mas de un registro");
        return (ids.length != 1) ? of(null) : of(ids[0]);
      }
    )
  }


  count (entity: string, data: any = null): Observable<any> {
    let key = "_" + entity + "_count" + JSON.stringify(data);
    if(this.storage.keyExists(key)) return of(this.storage.getItem(key));

    let url = API_ROOT + entity + '/count'
    return this.http.post<any>(url, "data="+JSON.stringify(data), HTTP_OPTIONS).map(
      res => {
        this.storage.setItem(key, res);
        return res;
      }
    );
  }

  //inicializacion de campos para ser presentados en un componente de una entidad determinada
  //@param entity Entidad
  //@param row Campos sin formatear, en el fieldset habitualmente se invoca a esta funcion para formatear los datos de forma tal que se adapten a los requerimientos del componente
  //@param sync Sincronizacion de campos
  //@return row Campos formateados, listos para ser presentados
  init (entity: string, row:{ [index: string]: any }, sync:{ [index: string]: any } = null): Observable<{ [index: string]: any }> {
    let ddi: DataDefinition = this.loader.dataDefinition(entity, this);
    return ddi.init(row, sync);
  }


  //inicializacion de campos para ser presentados en el fieldset de una entidad determinada para un formulario
  //@param entity Entidad
  //@param row Campos sin formatear, en el fieldset habitualmente se invoca a esta funcion para formatear los datos de forma tal que se adapten a los requerimientos del componente
  //@param sync Sincronizacion de campos
  //@return row Campos formateados, listos para ser presentados
  initForm (entity: string, row:{ [index: string]: any }, sync:{ [index: string]: any } = null): Observable<{ [index: string]: any }> {
    let ddi: DataDefinition = this.loader.dataDefinition(entity, this);
    return ddi.initForm(row, sync);
  }


  labelGet (entity: string, id: string | number): Observable<any> {
    return this.get(entity, id).mergeMap(
      row => {
        return this.init(entity, row).mergeMap(
          row_ => {
            let ddi: DataDefinition = this.loader.dataDefinition(entity, this);
            return ddi.label(row_).mergeMap(
              label => { return of(label); }
            );
          }
        );
      }
    );
  }

  labelGet (entity: string, id: string | number): Observable<any> {
    return this.getOrNull(entity, id).mergeMap(
      row => {
        if(!row) return of(null);
        return this.labelGet(entity, id);
      }
    );
  }

  initLabel (entity: string, row:any): Observable<any>{
    return this.init(entity, row).mergeMap(
      row => {
        let ddi: DataDefinition = this.loader.dataDefinition(entity, this);
        return ddi.label(row).mergeMap(
          label => {
            row.label = label;
            return of(row);
          }
        );
      }
    );
  }

  initLabelAll (entity: string, rows:any[]): Observable<any> {
    var obs = [];

    if(!rows.length) return of([])

    for (var i = 0; i < rows.length; i++) {
      var ob = this.initLabel(entity, rows[i]);
      obs.push(ob);
    }

    return Observable.forkJoin(obs);
  }

  //inicializar filtros de busqueda
  initFilters (entity: string, filters: Array<any>): Observable<any> {
    let ddi: DataDefinition = this.loader.dataDefinition(entity, this);
    return ddi.initFilters(filters);
  }

  //envio de filtros
  serverFilters (entity: string, filters: Array<any>): Array<any> {
    let ddi: DataDefinition = this.loader.dataDefinition(entity, this);
    return ddi.serverFilters(filters);
  }

  //definir estructura de formularios
  formGroup (entity: string, sync:any): FormGroup {
    let ddi: DataDefinition = this.loader.dataDefinition(entity, this);
    return ddi.formGroup(sync);
  }

  //definir datos para ser enviados al servidor
  server (entity: string, row:{ [index: string]: any }): { [index: string]: any } {
    let ddi: DataDefinition = this.loader.dataDefinition(entity, this);
    return ddi.server(row);
  }

  checkTransaction(){
    let url = API_ROOT + 'system/checkTransaction'
    return this.http.get<any>(url).map(
      response => {
        if(!response["data"] || response["data"] == "null") return null;

        if(response["data"] == "CLEAR"){  this.storage.clear(); }

        else if(response["data"]) {
         this.storage.removeItems(response["data"]);
         this.storage.removeItemsPrefix("_");
        }

        return response["data"];
      }
    );
  }

  //envia un conjunto de datos para ser procesados, retorna un array con los ids persistidos
  process(data: any[]){
    let url = API_ROOT + 'system/process'

    return this.http.post<any>(url, "data="+JSON.stringify(data), HTTP_OPTIONS).map(
      response => {
        this.message.add("Se efectuado un registro de datos");
        return response;
      }
    )
  }

  //eliminar entidad
  delete(entity:string, id:string|number): Observable<any> {
    let url = API_ROOT + entity + '/delete'

    return this.http.post<any>(url, "id="+JSON.stringify(id), HTTP_OPTIONS).map(
      response => {
        if(response.status) this.message.add("Se ha eliminado " + entity);
        else this.message.add("No se puede eliminar " + entity + ": " + response.message)
        return response;
      }
    )
  }



}
