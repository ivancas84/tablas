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
  //  "d/m/Y",
  //  "object": Retorna un objeto {day:number, month:number, year:number}, inspirado en el datepicker de bootstrap
  dateFormat(value: Date, format: string = null): any {
    if(!(value instanceof Date)){
      if(format = "object") return { day:null, month:null, year:null };
      else { return null; }
    }

    //se asigna un numero a las variables porque sino tira error en compliacion: ubsequent variable declarations must have the same type
    switch(format){
      case "F": return this.MONTH_NAMES[value.getMonth()];

      case "d/m/Y":
        var yyyy = value.getFullYear().toString();
        var mm = (value.getMonth()+1).toString();
        var dd  = value.getDate().toString();
        return (dd[1]?dd:"0"+dd[0]) + "/" + (mm[1]?mm:"0"+mm[0]) + "/" + yyyy;


      case "object":
        var yyyy2 = value.getFullYear();
        var mm2 = value.getMonth()+1;
        var dd2  = value.getDate();
        return {day:dd2, month:mm2, year:yyyy2};

      default:
        var yyyy3 = value.getFullYear().toString();
        var mm3 = (value.getMonth()+1).toString();
        var dd3  = value.getDate().toString();
        return yyyy3 + "-" + (mm3[1]?mm3:"0"+mm3[0]) + "-" + (dd3[1]?dd3:"0"+dd3[0]);
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
