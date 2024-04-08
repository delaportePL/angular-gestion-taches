import { Component, OnDestroy, OnInit } from '@angular/core';
import { Observable, Subject } from 'rxjs';
import { Task } from '../models/task.model';
import { TaskState } from '../enums/task-state.enum';
import { TaskCategory } from '../enums/task-category.enum';

@Component({
  selector: 'app-task-list',
  templateUrl: './task-list.component.html',
  styleUrls: ['./task-list.component.css']
})
export class TaskListComponent implements OnInit, OnDestroy {
  private _unsubscribeAll: Subject<void> = new Subject<void>();
  tasks$!: Observable<Task[]>;
  tasks!: Task[];

  ngOnInit(): void {
    this.tasks = [
      {
        id: "1",
        title: "Exemple de titre tâche 1",
        description: "Il faut faire ceci cela",
        state: TaskState.ONGOING,
        user_id: "1",
        points: 3,
        category: TaskCategory.TECH,
      },
      {
        id: "2",
        title: "Exemple de titre tâche 2",
        description: "Il faut faire ceci cela",
        state: TaskState.DONE,
        user_id: "1",
        points: 2,
        category: TaskCategory.IMPROVE,
      },
      {
        id: "3",
        title: "Exemple de titre tâche 3",
        description: "Il faut faire ceci cela",
        state: TaskState.TODO,
        user_id: "2",
        points: 4,
        category: TaskCategory.BUG,
      },
    ]
  }

  ngOnDestroy(): void {
    this._unsubscribeAll.next();
    this._unsubscribeAll.complete();
  }

}
