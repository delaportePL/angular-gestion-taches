import { Injectable } from '@angular/core';
import { Observable, BehaviorSubject } from 'rxjs';
import { Task } from '../models/task.model';

@Injectable({
  providedIn: 'root'
})
export class TaskService {
  private tasks: BehaviorSubject<Task[]> = new BehaviorSubject<Task[]>([]);

  constructor() {}

  getTasks(): Observable<Task[]> {
    return this.tasks.asObservable();
  }

  addTask(task: Task): void {
    const currentTasks = this.tasks.getValue();
    const updatedTasks = [...currentTasks, task];
    this.tasks.next(updatedTasks);
  }

  updateTask(updatedTask: Task): void {
    const currentTasks = this.tasks.getValue();
    const index = currentTasks.findIndex(task => task.id === updatedTask.id);
    if (index !== -1) {
      currentTasks[index] = updatedTask;
      this.tasks.next(currentTasks);
    }
  }

  deleteTask(taskId: number): void {
    const currentTasks = this.tasks.getValue();
    const updatedTasks = currentTasks.filter(task => task.id !== taskId);
    this.tasks.next(updatedTasks);
  }
}
