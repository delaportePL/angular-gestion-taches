import { Component, EventEmitter, Input, OnInit, Output } from '@angular/core';
import { Task } from '../models/task.model';
import { TaskService } from '../services/task.service';
import { User } from '../models/user.model';

@Component({
  selector: 'app-task-minimized',
  templateUrl: './task-minimized.component.html',
  styleUrls: ['./task-minimized.component.css']
})
export class TaskMinimizedComponent implements OnInit{
  @Input() task!: Task;
  @Output() onDeleteTaskEvent: EventEmitter<string> = new EventEmitter<string>();
  
  @Input() users!: User[];
  assignedUser!: User | undefined;

  constructor() { }

  ngOnInit(): void {
    if(this.users) {
      this.assignedUser = this.users.find(user => user._id.$oid === this.task.assignedUserId);
    }
  }

  onDeleteClick(task: Task): void {
    this.onDeleteTaskEvent.emit(task._id.$oid);
  }
}
