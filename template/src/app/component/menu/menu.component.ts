import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-menu',
  templateUrl: './menu.component.html',
  styleUrls: ['./menu.component.css']
})
export class MenuComponent {

  public isCollapsed = true;

 toggleMenu() {
    this.isCollapsed = !this.isCollapsed;
  }

}
