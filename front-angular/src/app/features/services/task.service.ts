import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable, BehaviorSubject } from 'rxjs';

import { Task } from '../models/task.model';

@Injectable({
  providedIn: 'root'
})
export class TaskService {
  private tasks: BehaviorSubject<Task[]> = new BehaviorSubject<Task[]>([]);
  private apiUrl = 'https://your-backend-api-url/tasks';

  constructor(private httpClient: HttpClient) {}

  getTasks(): Observable<Task[]> {
    const url = this.apiUrl + "/list";

    return this.httpClient.get<Task[]>(url);
  }

  addTask(task: Task): Observable<Task> {
    const url = this.apiUrl + "/list";

    return this.httpClient.post<Task>(url, task);
  }

  updateTask(updatedTask: Task): Observable<Task> {
    const url = `${ this.apiUrl }/update/${ updatedTask.id }`;

    return this.httpClient.put<Task>(url, updatedTask);
  }

  deleteTask(taskId: number): Observable<void> {
    const url = `${ this.apiUrl }/${ taskId }`;

    return this.httpClient.delete<void>(url);
  }
}
