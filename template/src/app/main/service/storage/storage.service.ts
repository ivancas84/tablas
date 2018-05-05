
export abstract class StorageService {

  constructor() { }

  getStorage(): any {
    return sessionStorage;
  }

  setItem(key: string, item: any): void {
      this.getStorage().setItem(key, JSON.stringify(item));
  }

  getItem(key: string): any {
      let data: any = this.getStorage().getItem(key);
      if (!data) return null;

      return JSON.parse(data);
  }


  removeItem(key): void { this.getStorage().removeItem(key); }

  clear(): void { this.getStorage().clear(); }

  keyExists(key: string): boolean {
    let s = this.getStorage();
    return (key in s) ? true : false;
  }

  removeItems(ids): void {
    let s = this.getStorage();
    for (let i in ids){
      if (ids.hasOwnProperty(i)) s.removeItem(ids[i]);
    }
  }

  removeItemsPrefix(prefix): void {
    let s = this.getStorage(),
        keys = Object.keys(s);

    for (let i in keys){
      if (keys.hasOwnProperty(i)){
        if(keys[i].indexOf(prefix) !== -1) s.removeItem(keys[i]);
      }
    }
  }

}
