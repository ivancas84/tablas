import { Component, Input, OnChanges, OnInit } from '@angular/core';
import { Display } from '../../class/display';
import { RouterService } from "../../service/router/router.service";
import { DataDefinitionService } from '../../../service/data-definition/data-definition.service';


//import {NgbPaginationConfig} from '@ng-bootstrap/ng-bootstrap';

@Component({
  selector: 'app-pagination',
  templateUrl: './pagination.component.html',
  //providers: [NgbPaginationConfig] // add NgbPaginationConfig to the component providers

})
export class PaginationComponent implements OnChanges {

  @Input() display: Display;
  @Input() entity: string;
  @Input() component: string;
  collectionSize: number = 10000; //debe ser inicializado, porque al reasignarlo asincronicamente si no tiene un valor asignado se carga la paginacion en 1

  constructor(protected dd: DataDefinitionService, protected router: RouterService) {}

  reload(){
    let sid = encodeURI(JSON.stringify(this.display));
    this.router.navigateByUrl('/' + this.component + '?sid=' + sid);
  }

  ngOnChanges() {
    this.dd.count(this.entity, this.display).subscribe(
      count => {
        this.collectionSize = count;
      }
    )
  }


}
