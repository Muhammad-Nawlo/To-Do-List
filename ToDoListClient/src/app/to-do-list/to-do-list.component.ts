import { Component, OnInit, Output } from '@angular/core';
import { TaskService } from '../services/task.service';

@Component({
  selector: 'app-to-do-list',
  templateUrl: './to-do-list.component.html',
  styleUrls: ['./to-do-list.component.scss'],
})
export class ToDoListComponent implements OnInit {
  btnStatus = true;
  tasks: any[] = [];
  constructor(private taskService: TaskService) {}

  ngOnInit(): void {
    this.taskService.getAll().subscribe((response: any) => {
      if (response.msg == 'ok') {
        this.tasks = response.tasks;
      }
    });
  }
  changeStatus(val: any) {
    this.btnStatus = val;
  }
  newTask(task: any) {
    this.tasks.push(task);
  }
  taskDeleted(id: string) {
    this.tasks = this.tasks.filter((task) => {
      return task._id != id;
    });
  }
}
