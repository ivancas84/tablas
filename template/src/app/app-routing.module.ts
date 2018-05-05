import { NgModule }             from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { OptionsComponent }      from './component/options/options.component';
//import { HelloWorldComponent }      from './component/hello-world/hello-world.component';

const routes: Routes = [
  { path: '', redirectTo: '/options', pathMatch: 'full' },
  { path: 'options', component: OptionsComponent },
  //{ path: 'hello-world', component: HelloWorldComponent },
];

@NgModule({
  imports: [ RouterModule.forRoot(routes, {onSameUrlNavigation: 'reload'}) ],
  exports: [ RouterModule ]
})
export class AppRoutingModule {}
