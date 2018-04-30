import { DataDefinitionService } from '../../../service/data-definition/data-definition.service';
import { Observable } from 'rxjs/Observable';
import { of } from 'rxjs/observable/of';

export abstract class DataDefinition {

  protected entity: string;

  constructor(protected dd: DataDefinitionService){ }

  abstract storage(row: { [index: string]: any }): void;
  abstract initFilters(filters: any[]): Observable<any>;
  abstract serverFilters(filters: any[]): any[];
  options(sync: any): Observable<any> { return null; };


  //deberia generarse solo si tiene tipos complejos como date, timestamp (por el momento se genera siempre)
  initMain(row: { [index: string]: any }): { [index: string]: any } {
    if(!row) return null;
    let row_: { [index: string]: any } = Object.assign({}, row);

    return row_;
  }

  init (row: { [index: string]: any }): Observable<{ [index: string]: any }> {
    let ret = "";

    let row_: { [index: string]: any } = this.initMain(row);

    return of(row_);
  }

  labelMain (row: { [index: string]: any }): string {
    let ret = "";
    if (row["id"]) ret = ret + " " + row["id"];

    return ret.trim();
  }

  label (row: { [index: string]: any }): Observable<string> {
    let ret: string = this.labelMain(row);

    return of(ret);
  }
}
