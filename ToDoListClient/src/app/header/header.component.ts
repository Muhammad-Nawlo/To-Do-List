import { Component, Input, Output, OnInit, EventEmitter } from '@angular/core';

@Component({
  selector: 'app-header',
  templateUrl: './header.component.html',
  styleUrls: ['./header.component.scss'],
})
export class HeaderComponent implements OnInit {
  @Input('btnStatus') btnStatus: boolean = false;
  @Output('changeStatus') changeStatus = new EventEmitter();
  constructor() {}
  ngOnInit(): void {}

  onChangeStatus(val: any) {
    this.changeStatus.emit(val);
  }
}
