import { Injectable } from '@angular/core';

@Injectable()
export class MessageService {
  messages: string[] = [];

  add(message: string) {
    this.messages.push(message);
  }

  remove(index) {
    this.messages.splice(index, 1);
  }

  clear() {
    this.messages = [];
  }
}
