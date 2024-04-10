import { Component, EventEmitter, Input, OnInit, Output } from '@angular/core';
import { Task } from '../models/task.model';
import { TaskService } from '../services/task.service';

@Component({
  selector: 'app-task-minimized',
  templateUrl: './task-minimized.component.html',
  styleUrls: ['./task-minimized.component.css']
})
export class TaskMinimizedComponent implements OnInit{
  @Input() task!: Task;
  @Output() onDeleteTaskEvent: EventEmitter<string> = new EventEmitter<string>();

  constructor(private taskService: TaskService) { }

  ngOnInit(): void {
      
  }

  onDeleteClick(task: Task): void {
    this.onDeleteTaskEvent.emit(task._id.$oid);
  }
}
