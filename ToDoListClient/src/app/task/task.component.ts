import { Component, EventEmitter, Input, OnInit, Output } from '@angular/core';
import { TaskService } from '../services/task.service';

@Component({
  selector: 'app-task',
  templateUrl: './task.component.html',
  styleUrls: ['./task.component.scss'],
})
export class TaskComponent implements OnInit {
  @Input('task') task: any = {};
  @Output('taskDeleted') taskDeleted = new EventEmitter();
  @Output('taskUpdated') taskUpdated = new EventEmitter();
  constructor(private taskService: TaskService) {}

  ngOnInit(): void {}
  deleteTask(task: any) {
    this.taskService.delete(task._id).subscribe((response: any) => {
      if (response.msg == 'ok') {
        this.taskDeleted.emit(task._id);
      }
    });
  }
  updateTask(task: any) {
    let params = {
      id: task._id,
      reminder: !task.reminder,
    };
    this.taskService.update(params).subscribe((response: any) => {
      if (response.msg == 'ok') {
        task.reminder = !task.reminder;
        this.taskUpdated.emit(task);
      }
    });
  }
}
