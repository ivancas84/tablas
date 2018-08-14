import { Input, OnInit, OnChanges, SimpleChanges } from '@angular/core';
import { FormArray, FormBuilder, FormGroup } from '@angular/forms';
import { Observable } from 'rxjs/Observable';
import 'rxjs/add/observable/forkJoin';


import { DataDefinitionService } from '../../../service/data-definition/data-definition.service';

export abstract class FieldsetArrayComponent implements OnChanges {
  entity: string; //entidad principal del componente
  fieldset: string; //nombre del fieldset
  options: {} = null; //opciones para el formulario

  @Input() fieldsetForm: FormArray; //formulario de administracion
  @Input() sync: any; //sincronizacion de componentes
  @Input() rows: any; //campos que seran formateados para ser presentados en el componente
  @Input() enable: boolean = true; //el padre puede habilitar o no el formulario

  constructor(protected fb: FormBuilder, protected dd: DataDefinitionService) { }

  isSync(field: string){ return this.dd.isSync(field, this.sync); }

  addRow() {
    this.dd.initForm(this.entity, null, this.sync).subscribe(
      row => {
        var fg = this.dd.formGroup(this.entity, this.sync);

        //fg.reset(row);
        console.log(fg);
        this.fieldsetForm.push(fg)
      }
    )
  }

  removeRow(index){
    this.fieldsetForm.removeAt(index);
  }

  ngOnChanges(changes: SimpleChanges) {
    if(changes.rows){ //changes.row.isFirstChange()
      this.dd.options(this.entity, this.sync).subscribe(
        options => {
          this.options = options;

          var obs = [];
          for(var i = 0; i < this.rows.length; i++){
            var ob = this.dd.initForm(this.entity, this.rows[i], this.sync)
            obs.push(ob);
          }

          Observable.forkJoin(obs).subscribe(
            responses => {
              for(var i = 0; responses.length; i++){
                var fg = this.dd.formGroup(this.entity, this.sync);
                fg.reset(responses[i]);
                this.fieldsetForm.push(fg);
              }
            }
          )
        }
      )
    }

    if(changes.enable && this.fieldsetForm){
      (changes.enable.currentValue) ? this.fieldsetForm.enable() : this.fieldsetForm.disable();
    }
  }


}
