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
  selector: 'app-filter-typeahead',
  templateUrl: './filter-typeahead.component.html',
})
export class FilterTypeaheadComponent {
  @Input() entity: string;
  @Input() filter: FormGroup;

  searching = false;

  constructor(public dd: DataDefinitionService) {  }

  isSelected(value) { return (value) && (typeof value === 'object') && (value.hasOwnProperty('id')) && (value.id); }

  searchTerm(term): Observable<any> {
    console.log(term)
    var display = new Display();
    display.search = term;
    return this.dd.all(this.entity, display);
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
          console.log(error);
          return of([]);
        }))
    .do(() => this.searching = false)

    formatter = (x: {nombre: string}) => x.nombre;
}
