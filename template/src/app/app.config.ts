import { HttpHeaders } from '@angular/common/http';
import { Field } from './main/class/field';

export const HTTP_OPTIONS = {
  headers: new HttpHeaders({
        'Content-Type' : 'application/x-www-form-urlencoded; charset=UTF-8'
    })
};

export const API_ROOT = 'http://localhost/prueba/api/';

export const ASIGNATURAS: Field[][] = [
  [{ name: 'id', value: "1" }, { name: 'nombre', value: "Matemática" }],
  [{ name: 'id', value: "2" }, { name: 'nombre', value: "Lengua y Literatura" }],
  [{ name: 'id', value: "3" }, { name: 'nombre', value: "Química" }]
]
