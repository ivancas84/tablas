import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { FormArray, FormBuilder, FormGroup, Validators } from '@angular/forms';

import { DataDefinitionMainService } from '../../main/service/data-definition/data-definition-main.service';
import { SessionStorageService } from '../../main/service/storage/session-storage.service';
import { ParserService } from '../../main/service/parser/parser.service';
import { MessageService } from '../../main/service/message/message.service';

import { DataDefinitionLoaderService } from './data-definition-loader.service';


@Injectable()
export class DataDefinitionService extends DataDefinitionMainService {

  constructor(public fb: FormBuilder, public http: HttpClient, public storage: SessionStorageService, public loader: DataDefinitionLoaderService, public parser: ParserService, public message: MessageService) {
    super(fb, http, storage, loader, parser, message);
  }

}
