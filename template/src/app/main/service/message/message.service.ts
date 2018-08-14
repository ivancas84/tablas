import { Injectable } from '@angular/core';

@Injectable()
export class MessageService {
  messages: string[] = [];

  add(message: string) {
    if (this.messages.length == 3) this.messages.pop();
    this.messages.unshift(message);
  }

  remove(index) {
    this.messages.splice(index, 1);
  }

  clear() {
    this.messages = [];
  }
}
