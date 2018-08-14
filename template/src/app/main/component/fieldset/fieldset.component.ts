import { Input, OnInit, OnChanges, SimpleChanges } from '@angular/core';
import { FormBuilder, FormGroup, AsyncValidatorFn, ValidationErrors } from '@angular/forms';


import { Output, EventEmitter } from '@angular/core';

import { DataDefinitionService } from '../../../service/data-definition/data-definition.service';

import { Display } from '../../class/display';

import 'rxjs/add/operator/map';



export abstract class FieldsetComponent implements OnChanges {
  entity: string; //entidad principal del componente
  fieldset: string; //nombre del fieldset
  options: {} = null; //opciones para el formulario

  @Input() adminForm: FormGroup; //formulario de administracion
  @Input() sync: any; //sincronizacion de componentes
  @Input() row: any; //campos que seran formateados para ser presentados en el componente
  @Input() enable: boolean = true; //el padre puede habilitar o no el formulario

  fieldsetForm: FormGroup;

  @Output() changeFieldset: EventEmitter<any> = new EventEmitter<any>(); //emitir cambio en el fieldset al padre, lo que se emite puede ser variable, en el padre se definiran las condiciones para su procesamiento

  constructor(protected fb: FormBuilder, protected dd: DataDefinitionService) { }

  isSync(field: string){ return this.dd.isSync(field, this.sync); }

  //cambios en el formulario
  setChange(){
    //TODO implementacion opcional
    //EXAMPLE this.changeUpdate("fieldName");
  }

  //comportamiento habitual buscar cambio en determinado campo
  //* Si el valor esta vacio, no se ejecuta ninguna accion.
  //* si el id esta definido, y se obtiene un id diferente, se genera un error
  //* Este método se define fuera de los validadores habituales debido a que dispara un evento y para evitar que haga un doble chequeo por modificación de campos.
  //* En el caso de utilizarse se recomienda deshabilitar los validadores asincrónicos que acceden a la base de datos al modificarse el field, ya que haran un doble chequeo.
  changeUpdate(fieldName: string) {
    var field = this.fieldsetForm.get(fieldName);
    field.valueChanges.map(
      (value: string) => {
        if(value){
          if(!field.errors) field.setErrors({'valueChanges':true})
          return value;
        }
      }
    ).debounceTime(1000).subscribe(
      (value: string) => {
        if(value) {
          if(field.getError("valueChanges")) field.setErrors(null); //debería ser field.setErrors({"valueChanges":null}) pero esta opcion actualmente no funciona, posteriormente se probara

          let display: Display = new Display;
          display.filters = [fieldName, "=", value]
          this.dd.idOrNull(this.entity, display).subscribe(
            id => {
              if(this.row && this.row["id"] && id){
                if(id != this.row["id"]) {
                  field.setErrors({ notUnique: true });
                }
              } else if(id){
                this.changeFieldset.emit(id);
              }
            },
            error => { console.log(error); }
          );
        }
      }
    )
  }





  ngOnChanges(changes: SimpleChanges) {
    if(changes.row){ //changes.row.isFirstChange()
      this.dd.options(this.entity, this.sync).subscribe(
        options => {
          this.options = options;

          if(changes.row.isFirstChange()){
            this.fieldsetForm = this.dd.formGroup(this.entity, this.sync);
            this.adminForm.addControl(this.fieldset, this.fieldsetForm);
            this.setChange();
          }


          this.dd.initForm(this.entity, this.row, this.sync).subscribe(
            row => {
              this.fieldsetForm.reset(row);
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
