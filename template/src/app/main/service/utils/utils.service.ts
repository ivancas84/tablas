import { Injectable } from '@angular/core';
import { ActivatedRoute, Router, NavigationEnd } from '@angular/router';

@Injectable()
export class UtilsService {

  constructor(protected router: Router) { }

  uniqueId(): string {
    let start = new Date().getTime();
    while (new Date().getTime() < start + 1); //esperar un microsegundo para evitar colisiones en caso de multiples llamados al metodo

    let date = Date.now().toString();
    let number =  (Math.floor(Math.random()*10000)).toString(); //numero aleatorio de 4 posisiones
    if ( (4 - number.length) > 0 ) number = "0" + number;
    return date+number;
  }

  //fix angular bug
  navigate(link: string){
    this.router.routeReuseStrategy.shouldReuseRoute = function(){ return false; };

    this.router.events.subscribe((evt) => {
      if (evt instanceof NavigationEnd) {
          this.router.navigated = false;
          window.scrollTo(0, 0);
      }
    });

    this.router.navigateByUrl(link)
  }

}
