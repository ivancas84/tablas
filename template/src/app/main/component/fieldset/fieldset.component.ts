import { Input, OnInit, OnChanges, SimpleChanges } from '@angular/core';
import { FormBuilder, FormGroup } from '@angular/forms';

import { DataDefinitionService } from '../../../service/data-definition/data-definition.service';

export abstract class FieldsetComponent implements OnChanges {
  entity: string; //entidad principal del componente
  fieldset: string; //nombre del fieldset
  options: {} = null; //opciones para el formulario


  @Input() adminForm: FormGroup;
  @Input() sync: any;
  @Input() row: any;

  fieldsetForm: FormGroup;
  constructor(protected fb: FormBuilder, protected dd: DataDefinitionService) { }

  isSync(field: string){ return this.dd.isSync(field, this.sync); }

  ngOnChanges(changes: SimpleChanges) {
    if(changes.row){ //changes.row.isFirstChange()
      this.dd.options(this.entity, this.sync).subscribe(
        options => {
          this.options = options;

          if(changes.row.isFirstChange()){
            this.fieldsetForm = this.dd.formGroup(this.entity, this.sync);
            this.adminForm.addControl(this.fieldset, this.fieldsetForm);
          }

          this.dd.initForm(this.entity, this.row, this.sync).subscribe(
            row => {
              this.fieldsetForm.reset(row);
            }
          )
        }
      )
    }
  }


}
