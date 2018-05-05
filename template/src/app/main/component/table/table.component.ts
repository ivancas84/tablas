import { Input, OnChanges, SimpleChanges } from '@angular/core';
import {NgbModal} from '@ng-bootstrap/ng-bootstrap';
import {ModalConfirmComponent} from '../modal-confirm/modal-confirm.component';

import { DataDefinitionService } from '../../../service/data-definition/data-definition.service';

export abstract class TableComponent implements OnChanges {
  entity: string; //entidad principal del componente

  @Input() data: any; //datos a formatear
  rows: any = []; //datos a visualizar

  @Input() sync: any = []; //configuracion de sincronizacion

  constructor(protected dd: DataDefinitionService, protected modalService: NgbModal) { }

  isSync(field: string){ return this.dd.isSync(field, this.sync); }

  order(){
    console.log("en construccion");
  }

  ngOnChanges(changes: SimpleChanges) {
    if(changes.data){ //changes.row.isFirstChange()

      for (let i = 0; i < this.data.length; i++) {
        this.dd.init(this.entity, this.data[i]).subscribe(
          row => { this.rows.push(row); }
        );
      }
    }
  }

  delete(index:number) {
    const modalRef = this.modalService.open(ModalConfirmComponent);
    this.dd.labelGet(this.entity, this.rows[index].id).subscribe(
      label => { modalRef.componentInstance.label = label; }
    )

    modalRef.result.then(
      (result) => {
        if(result){
          this.dd.delete(this.entity, this.rows[index].id).subscribe(
            response => { if(response.status) this.rows.splice(index, 1); }
          )
        }
      },
      (reason) => { console.log(reason); }
    );
  }
}
