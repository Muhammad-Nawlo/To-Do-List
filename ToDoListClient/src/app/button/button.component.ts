import { Component, EventEmitter, Input,Output } from '@angular/core';

@Component({
  selector: 'app-button',
  templateUrl: './button.component.html',
  styleUrls: ['./button.component.scss'],
})
export class ButtonComponent {
  @Input('btnStatus') btnStatus: boolean = false;
  @Output('changeStatus') changeStatus = new EventEmitter()
  constructor() {}
  onChangeStatus(){
    this.btnStatus = !this.btnStatus;
    this.changeStatus.emit(this.btnStatus);
  }
}
