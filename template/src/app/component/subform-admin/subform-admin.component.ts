import { Component, OnInit, Input } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';

import { DataDefinitionService } from '../../service/data-definition/data-definition.service';


@Component({
  selector: 'app-subform-admin',
  templateUrl: './subform-admin.component.html',
  styleUrls: ['./subform-admin.component.css']
})
export class SubformAdminComponent implements OnInit {

  @Input() fieldset: FormGroup;


  constructor(protected fb: FormBuilder, protected dd: DataDefinitionService, protected route: ActivatedRoute) {

  }

  ngOnInit() {
  }
}
