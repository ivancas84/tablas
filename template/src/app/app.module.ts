import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { HttpClientModule }    from '@angular/common/http';
import { FormsModule } from '@angular/forms';
import { ReactiveFormsModule } from '@angular/forms';
import { NgbModule } from '@ng-bootstrap/ng-bootstrap';

import { DataDefinitionService } from './service/data-definition/data-definition.service';
import { DataDefinitionLoaderService } from './service/data-definition/data-definition-loader.service';
import { SessionStorageService } from './main/service/storage/session-storage.service';
import { LocalStorageService } from './main/service/storage/local-storage.service';
import { ParserService } from './main/service/parser/parser.service';
import { RouterService } from './main/service/router/router.service';
import { MessageService } from './main/service/message/message.service';

 


import { AppComponent } from './app.component';
import { AppRoutingModule } from './app-routing.module';

import { OptionsComponent } from './component/options/options.component';
import { MenuComponent } from './component/menu/menu.component';

import { PaginationComponent } from './main/component/pagination/pagination.component';
import { FilterTypeaheadComponent } from './main/component/filter-typeahead/filter-typeahead.component';
import { FieldsetTypeaheadComponent } from './main/component/fieldset-typeahead/fieldset-typeahead.component';
import { MessagesComponent } from './main/component/messages/messages.component';
import { ModalConfirmComponent } from './main/component/modal-confirm/modal-confirm.component'


//import { HelloWorldComponent } from './component/hello-world/hello-world.component';


@NgModule({
  declarations: [
    AppComponent,
    OptionsComponent,
    MenuComponent,
    PaginationComponent,
    FilterTypeaheadComponent,
    FieldsetTypeaheadComponent,

    MessagesComponent,
    ModalConfirmComponent,
    //HelloWorldComponent,
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    HttpClientModule,
    FormsModule,
    ReactiveFormsModule,
    NgbModule.forRoot()
  ],
  entryComponents: [ModalConfirmComponent],
  providers: [DataDefinitionService, DataDefinitionLoaderService, LocalStorageService, SessionStorageService, ParserService, RouterService, MessageService],
  bootstrap: [AppComponent]
})
export class AppModule { }
