import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { HttpClientModule }    from '@angular/common/http';
import { ReactiveFormsModule } from '@angular/forms';
import { NgbModule } from '@ng-bootstrap/ng-bootstrap';

import { DataDefinitionService } from './service/data-definition/data-definition.service';
import { DataDefinitionLoaderService } from './service/data-definition/data-definition-loader.service';
import { SessionStorageService } from './main/service/storage/session-storage.service';
import { LocalStorageService } from './main/service/storage/local-storage.service';
import { ParserService } from './main/service/parser/parser.service';

import { AppComponent } from './app.component';
import { AppRoutingModule } from './app-routing.module';

import { OptionsComponent } from './component/options/options.component';
import { MenuComponent } from './component/menu/menu.component';

//import { HelloWorldComponent } from './component/hello-world/hello-world.component';


@NgModule({
  declarations: [
    AppComponent,
    OptionsComponent,
    MenuComponent,
    //HelloWorldComponent,
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    HttpClientModule,
    ReactiveFormsModule,
    NgbModule.forRoot()
  ],
  providers: [DataDefinitionService, DataDefinitionLoaderService, LocalStorageService, SessionStorageService, ParserService],
  bootstrap: [AppComponent]
})
export class AppModule { }
