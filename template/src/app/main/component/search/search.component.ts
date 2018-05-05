import { Component, Input, OnChanges } from '@angular/core';
import { FormArray, FormBuilder, FormGroup, Validators } from '@angular/forms';
import { Observable } from 'rxjs/Observable';
import { of } from 'rxjs/observable/of';

import { Display } from "../../class/display";
import { Filter } from "../../class/filter";
import { RouterService } from "../../service/router/router.service";

import { DataDefinitionService } from '../../../service/data-definition/data-definition.service';

export abstract class SearchComponent implements OnChanges {
  @Input() display: Display;
  searchForm: FormGroup;
  entity: string; //entidad principal del componente
  options: {} = null; //opciones para el formulario
  sync: Array<any> = null;
  url: string; //url de acceso

  constructor(protected fb: FormBuilder, protected dd: DataDefinitionService, protected router: RouterService)  {
    this.createForm();
  }

  createForm(){
    this.searchForm = this.fb.group({
      search: '',
      filters: this.fb.array([]),
    })
  }

  setFilters(filters: Array<Filter>) {
    const filtersFGs = filters.map(filter => this.fb.group(filter));
    const filtersFormArray = this.fb.array(filtersFGs);
    this.searchForm.setControl('filters', filtersFormArray);
  }

  get filters(): FormArray { return this.searchForm.get('filters') as FormArray; }
  addFilter() { this.filters.push(this.fb.group(new Filter())); }
  removeFilter(index) { this.filters.removeAt(index); }

  onSubmit() {
    var formModel = this.searchForm.value;

    var filters: Filter[] = formModel.filters.map(
      filter => Object.assign({}, filter)
    )

    this.display.search = formModel.search;
    this.display.filters = this.dd.serverFilters(this.entity, filters);
    let sid = encodeURI(JSON.stringify(this.display));
    this.router.navigateByUrl('/' + this.url + '?sid=' + sid);
  }




  ngOnChanges() {
    this.dd.options(this.entity, this.sync).subscribe(
      options => {
        this.options = options;

        //let filters_: any[] =  Object.assign([], this.display.filters);
        this.dd.initFilters(this.entity, this.display.filters).subscribe(
          filters_ => {
            this.searchForm.reset({
              search: this.display.search
            });
            this.setFilters(filters_);
          }
        );
      }
    );
  }


}
