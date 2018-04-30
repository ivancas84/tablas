import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs/Observable';
import { of } from 'rxjs/observable/of';
import 'rxjs/add/operator/map';
import 'rxjs/add/operator/mergeMap';
import 'rxjs/add/observable/forkJoin';
import 'rxjs/add/operator/catch';




import { SessionStorageService } from '../storage/session-storage.service';
import { ParserService } from '../parser/parser.service';

import { HTTP_OPTIONS, API_ROOT } from '../../../app.config';
import { DataDefinitionLoaderService } from '../../../service/data-definition/data-definition-loader.service';
import { Display } from '../../class/display';
import { DataDefinition } from './data-definition';



export class DataDefinitionMainService {

  constructor(public http: HttpClient, public storage: SessionStorageService, public loader: DataDefinitionLoaderService, public parser: ParserService) { }


  //field sincronizado? Un field se considera "sincronizado" si es estrictamente distinto de false
  isSync(key, sync): boolean { return (!(key in sync) || sync[key]) ? true : false; }

  options(entity, sync): Observable<any> {
    let ddi: DataDefinition = this.loader.getInstance(entity, this);
    return ddi.options(sync);
  }

  all (entity: string, display: Display = null): Observable<any> {
    return this.ids(entity, display).mergeMap(
      ids => {
        if(!ids.length) { return of([]) }
        else {
          return this.getAll(entity, ids)
        }
      }
    )
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
        return this.http.post<any>(url, "ids="+JSON.stringify(searchIds), HTTP_OPTIONS).mergeMap(
          rows_ => {
            for(let i = 0; i < rows_.length; i++){
              let ddi: DataDefinition = this.loader.getInstance(entity, this);
              ddi.storage(rows_[i]);
              let i_string: string = String(rows_[i].id);
              let i_int: number = parseInt(i_string);
              let j: string | number = ids.indexOf(i_string);
              if(j == -1){ j = ids.indexOf(i_int); } //BUG: chequear por ambos tipos
              rows[j] = rows_[i];
            }

            return of(rows);
          }
        );
    } else {
      return of(rows);
    }
  }

  ids (entity: string, display: Display = null): Observable<any> {
    let key = "_ids" + entity + JSON.stringify(display);
    if(this.storage.keyExists(key)) return of(this.storage.getItem(key));

    let url = API_ROOT + entity + '/ids'
    return this.http.post<any>(url, "data="+JSON.stringify(display), HTTP_OPTIONS).map(
      ids => {
        this.storage.setItem(key, ids);
        return ids;
      }
    );
  }

  count (entity: string, data: any = null): Observable<any> {
    let key = "_count" + JSON.stringify(data);
    if(this.storage.keyExists(key)) return of(this.storage.getItem(key));

    let url = API_ROOT + entity + '/count'
    return this.http.post<any>(url, "data="+JSON.stringify(data), HTTP_OPTIONS).mergeMap(
      res => {
        this.storage.setItem(key, res);
        return of(res);
      }
    );
  }

  init (entity: string, row:{ [index: string]: any }): Observable<{ [index: string]: any }> {
    let ddi: DataDefinition = this.loader.getInstance(entity, this);
    return ddi.init(row);
  }

  labelGet (entity: string, id: string | number): Observable<any> {
    return this.get(entity, id).mergeMap(
      row => {
        return this.init(entity, row).mergeMap(
          row_ => {
            let ddi: DataDefinition = this.loader.getInstance(entity, this);
            return ddi.label(row_).mergeMap(
              label => { return of(label); }
            );
          }
        );
      }
    );
  }

  labelGetOrNull (entity: string, id: string | number): Observable<any> {
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
        let ddi: DataDefinition = this.loader.getInstance(entity, this);
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

    for (var i = 0; i < rows.length; i++) {
      var ob = this.initLabel(entity, rows[i]);
      obs.push(ob);
    }

    return Observable.forkJoin(obs);
  }

  //inicializar filtros de busqueda
  initFilters (entity: string, filters: Array<any>): Observable<any> {
    let ddi: DataDefinition = this.loader.getInstance(entity, this);
    return ddi.initFilters(filters);
  }

  //envio de filtros
  serverFilters (entity: string, filters: Array<any>): Array<any> {
    let ddi: DataDefinition = this.loader.getInstance(entity, this);
    return ddi.serverFilters(filters);
  }

}
