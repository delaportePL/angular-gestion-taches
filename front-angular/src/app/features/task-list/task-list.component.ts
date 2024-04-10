import { Component, OnDestroy, OnInit } from '@angular/core';
import { Observable, Subject } from 'rxjs';
import { Task } from '../models/task.model';
import { TaskState } from '../enums/task-state.enum';
import { TaskCategory } from '../enums/task-category.enum';
import { TaskService } from '../services/task.service';
import { TaskResponse } from '../models/task-response.model';
import { Project } from '../models/project.model';

@Component({
  selector: 'app-task-list',
  templateUrl: './task-list.component.html',
  styleUrls: ['./task-list.component.css']
})
export class TaskListComponent implements OnInit, OnDestroy {
  private _unsubscribeAll: Subject<void> = new Subject<void>();
  tasks!: Task[];

  projects: Project[] = [
    { projectId: "BRP", label: "Automatisation des tests", tasks: [] },
    { projectId: "UX", label:"Amélioration UX", tasks: []}
  ]

  constructor(private taskService: TaskService) {}


  ngOnInit(): void {
    // this.tasks = [
    //   {
    //     id: "1",
    //     title: "Exemple de titre tâche 1",
    //     description: "Il faut faire ceci cela",
    //     state: TaskState.ONGOING,
    //     user_id: "1",
    //     points: 3,
    //     category: TaskCategory.TECH,
    //   },
    //   {
    //     id: "2",
    //     title: "Exemple de titre tâche 2",
    //     description: "Il faut faire ceci cela",
    //     state: TaskState.DONE,
    //     user_id: "1",
    //     points: 2,
    //     category: TaskCategory.IMPROVE,
    //   },
    //   {
    //     id: "3",
    //     title: "Exemple de titre tâche 3",
    //     description: "Il faut faire ceci cela",
    //     state: TaskState.TODO,
    //     user_id: "2",
    //     points: 4,
    //     category: TaskCategory.BUG,
    //   },
    // ];
    this.taskService.getTasks().subscribe(tasks => {
      this.tasks = tasks;
      this.projects.forEach(project => {
        project.tasks = this.tasks.filter(task => task.projectId == project.projectId);
      });
    });
  }


  deleteTask(taskId: string) {
    this.taskService.deleteTask(taskId).subscribe(response => {
      if(response.tasks) {
        this.tasks = response.tasks;
      } else {
        console.error("Une erreur est survenue");
      }
    });
  }

  ngOnDestroy(): void {
    this._unsubscribeAll.next();
    this._unsubscribeAll.complete();
  }

}
