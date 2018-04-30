
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

  clear(): void {
    this.getStorage().clear();
  }

  keyExists(key: string): boolean {
    let s = this.getStorage();
    return (key in s) ? true : false;
  }


}
