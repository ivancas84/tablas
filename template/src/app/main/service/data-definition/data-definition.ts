import { FormBuilder, FormGroup, AsyncValidatorFn, AbstractControl, ValidationErrors } from '@angular/forms';

import { NgbDateStruct } from '@ng-bootstrap/ng-bootstrap';
import { NgbTimeStruct } from '@ng-bootstrap/ng-bootstrap';

import { Observable } from 'rxjs/Observable';
import { of } from 'rxjs/observable/of';
import 'rxjs/add/operator/map';
import 'rxjs/add/operator/mergeMap';
import 'rxjs/add/observable/timer';

import { DataDefinitionService } from '../../../service/data-definition/data-definition.service';
import { Display } from '../../../main/class/display';


export abstract class DataDefinition {
  entity: string;

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

  //custom async validator: verificar campo unico
  checkUniqueField(fieldName: string): AsyncValidatorFn {
    return (control: AbstractControl): Observable<ValidationErrors | null> => {
      let display: Display = new Display;
      display.filters = [fieldName, "=", control.value]

      return Observable.timer(1000).mergeMap(()=> {
        return this.dd.idOrNull(this.entity, display).map(
          id => {
            return (id && (id != control.parent.get("id").value)) ? { notUnique: true } : null
          }
        );
      })

    };
  }


}
