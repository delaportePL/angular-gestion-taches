import { Component, Input, OnInit } from '@angular/core';
import { Task } from '../models/task.model';

@Component({
  selector: 'app-task-minimized',
  templateUrl: './task-minimized.component.html',
  styleUrls: ['./task-minimized.component.css']
})
export class TaskMinimizedComponent implements OnInit{
  @Input() task!: Task;

  ngOnInit(): void {
      
  }
}
