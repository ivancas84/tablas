import { Input, OnChanges, SimpleChanges } from '@angular/core';
import {NgbModal} from '@ng-bootstrap/ng-bootstrap';
import {ModalConfirmComponent} from '../modal-confirm/modal-confirm.component';

import { DataDefinitionService } from '../../../service/data-definition/data-definition.service';
import { Display } from "../../class/display";
import { RouterService } from "../../service/router/router.service";

export abstract class TableComponent implements OnChanges {
  entity: string; //entidad principal del componente
  @Input() display: Display;


  @Input() data: any; //datos a formatear
  rows: any = []; //datos a visualizar
  order: any = {}; //configuracion de ordenamiento
  url: string; //url de acceso


  @Input() sync: any = []; //configuracion de sincronizacion

  constructor(protected dd: DataDefinitionService, protected modalService: NgbModal, protected router: RouterService) { }

  isSync(field: string){ return this.dd.isSync(field, this.sync); }

  setOrder(){

    var keys = Object.keys(this.display.order);

    if((keys.length) && (arguments[0] == keys[0])){
      var type: string = (this.display.order[keys[0]].toLowerCase() == "asc") ? "desc" : "asc";
      this.display.order[keys[0]] = type;
    } else {
      var obj = {}
      for(var i = 0; i < arguments.length; i++) obj[arguments[i]] = "asc";
      this.display.order = Object.assign(obj, this.display.order);
    }

    console.log(this.display.order);
    let sid = encodeURI(JSON.stringify(this.display));
    this.router.navigateByUrl('/' + this.url + '?sid=' + sid);

  };




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
