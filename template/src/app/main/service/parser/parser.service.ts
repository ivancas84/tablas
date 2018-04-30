import { Injectable } from '@angular/core';

@Injectable()
export class ParserService {

  readonly MONTH_NAMES = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
  readonly MONTH_NAMES_SHORT = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];


  constructor() { }

  date(value: string): Date {
    switch(value){
      case null: return null;

      case "CURRENT_TIME": case "CURRENT_DATE": case "CURRENT_TIMESTAMP":
        let date: Date = new Date();
        return new Date(date.getTime() + date.getTimezoneOffset() * 60 * 1000); //sumamos offset correspondiente a la hora local (offset_minutos * segundos * transformar_a_milisegundos)

      default:
        date = new Date(value);
        return new Date(date.getTime() + date.getTimezoneOffset() * 60 * 1000); //sumamos offset correspondiente a la hora local (offset_minutos * segundos * transformar_a_milisegundos)
    }
  }

  //@param format (default Y-m-d)
  dateString(value: Date, format: string = null): string {
    if(!(value instanceof Date)) return null;

    let yyyy: string = null;
    let mm: string = null;
    let dd: string = null
    switch(format){
      case "F": return this.MONTH_NAMES[value.getMonth()];

      case "d/m/Y":
        yyyy = value.getFullYear().toString();
        mm = (value.getMonth()+1).toString();
        dd  = value.getDate().toString();
        return (dd[1]?dd:"0"+dd[0]) + "/" + (mm[1]?mm:"0"+mm[0]) + "/" + yyyy;

      default:
        yyyy = value.getFullYear().toString();
        mm = (value.getMonth()+1).toString();
        dd  = value.getDate().toString();
        return yyyy + "-" + (mm[1]?mm:"0"+mm[0]) + "-" + (dd[1]?dd:"0"+dd[0]);
      }
  }

  timestamp(value: string): Date {
    let date: Date = null;
    let time_ = null;
    let time = null;

    switch(value){
      case null: return null;

      case "CURRENT_TIME": case "CURRENT_DATE": case "CURRENT_TIMESTAMP":
        date = new Date();
        date.setSeconds(0);
        return new Date(date.getTime() - date.getTimezoneOffset() * 60 * 1000);

      default:
        date = new Date();
        time_ = value.split(" ");
        time = time_[1].split(":");

        date.setHours(Number(time[0]));
        date.setMinutes(Number(time[1]));
        date.setSeconds(0);
        return new Date(date.getTime() - date.getTimezoneOffset() * 60 * 1000);
    }
  }

}
