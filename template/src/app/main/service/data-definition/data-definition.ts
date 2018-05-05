import { FormBuilder, FormGroup } from '@angular/forms';

import { DataDefinitionService } from '../../../service/data-definition/data-definition.service';
import { Observable } from 'rxjs/Observable';
import { of } from 'rxjs/observable/of';

export abstract class DataDefinition {

  protected entity: string;

  constructor(protected dd: DataDefinitionService){ }

  abstract storage(row: { [index: string]: any }): void;
  abstract initFilters(filters: any[]): Observable<any>;
  abstract serverFilters(filters: any[]): any[];
  abstract formGroup (sync:{ [index: string]: any }): FormGroup;
  abstract init (row: { [index: string]: any }, sync:{ [index: string]: any }): Observable<{ [index: string]: any }>;
  abstract server (row: { [index: string]: any }): {[index: string]: any};
  abstract initForm (row: { [index: string]: any }, sync:{ [index: string]: any }): Observable<{ [index: string]: any }>;

  options(sync: any): Observable<any> { return of(null); };

  //@param row inicializado (init)
  label (row: { [index: string]: any }): Observable<string> {
    let ret = "";
    if (row["id"]) ret = ret + " " + row["id"];

    return of(ret);
  }




}
