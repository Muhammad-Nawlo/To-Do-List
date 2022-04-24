import { Component, EventEmitter, OnInit, Output } from '@angular/core';
import { TaskService } from '../services/task.service';

@Component({
  selector: 'app-task-form',
  templateUrl: './task-form.component.html',
  styleUrls: ['./task-form.component.scss'],
})
export class TaskFormComponent implements OnInit {
  @Output('taskSaved') taskSaved = new EventEmitter();

  constructor(private taskService: TaskService) {}

  ngOnInit(): void {}
  saveTask(data: any) {
    this.taskService.add(data).subscribe((response: any) => {
      if (response.msg === 'ok') {
        this.taskSaved.emit(response.newTask);
      }
    });
  }
}
