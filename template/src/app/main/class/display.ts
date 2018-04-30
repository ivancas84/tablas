import { Filter } from './filter';

export class Display {
  size: number = 100;
  page: number = 1;
  search: string = "";
  order: Array<any> = [];
  params: Object = {};
  filters: Array<Filter> = [];
  export?: string;
}
