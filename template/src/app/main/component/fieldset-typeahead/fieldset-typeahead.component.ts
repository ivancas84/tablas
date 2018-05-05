import { Component, Input, OnChanges } from '@angular/core';
import { FormArray, FormBuilder, FormGroup, Validators } from '@angular/forms';

import {Observable} from 'rxjs/Observable';
import {of} from 'rxjs/observable/of';
import 'rxjs/add/operator/catch';
import 'rxjs/add/operator/debounceTime';
import 'rxjs/add/operator/do';
import 'rxjs/add/operator/switchMap';


import { Display } from "../../class/display";
import { Filter } from "../../class/filter";

import { DataDefinitionService } from '../../../service/data-definition/data-definition.service';

@Component({
  selector: 'app-fieldset-typeahead',
  templateUrl: './fieldset-typeahead.component.html',
})
export class FieldsetTypeaheadComponent {
  @Input() entity: string;
  @Input() fieldsetForm: FormGroup;
  @Input() field: string;

  searching = false;

  constructor(public dd: DataDefinitionService) {  }

  isSelected(field) {
    let value = this.fieldsetForm.get(field).value;
    return (value) && (typeof value === 'object') && (value.hasOwnProperty('id')) && (value.id);
  }

  searchTerm(term): Observable<any> {
    var display = new Display();
    display.search = term;
    return this.dd.all(this.entity, display).mergeMap(
      rows => {
        return this.dd.initLabelAll(this.entity, rows);
      }
    );
  }

  search = (text$: Observable<string>) =>
    text$
    .debounceTime(500)
    //.distinctUntilChanged()
    .do(() => this.searching = true)
    .switchMap(term =>
      term.length < 2 ?
        of([]) : this.searchTerm(term)
        .catch(error => {
          return of([]);
        }))
    .do(() => this.searching = false)

    formatter = (x: {label: string}) => x.label;
}
