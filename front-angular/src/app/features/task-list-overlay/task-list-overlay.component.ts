import { Component } from '@angular/core';
import { TaskService } from '../services/task.service';
import { Router } from '@angular/router';

@Component({
  selector: 'app-task-list-overlay',
  templateUrl: './task-list-overlay.component.html',
  styleUrls: ['./task-list-overlay.component.css']
})
export class TaskListOverlayComponent {

  constructor(private router: Router) {}

  onAddTaskClick() {
    this.router.navigateByUrl('ajouter-tache');
    // const task 
    // this.taskService.addTask(task);
  }
}
