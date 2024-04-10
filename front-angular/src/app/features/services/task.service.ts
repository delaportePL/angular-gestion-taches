import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable, BehaviorSubject, tap } from 'rxjs';

import { Task } from '../models/task.model';
import { TaskResponse } from '../models/task-response.model';

@Injectable({
  providedIn: 'root'
})
export class TaskService {
  private apiUrl = 'https://127.0.0.1:8000/api/tasks';

  constructor(private httpClient: HttpClient) {}

  getTasks(): Observable<Task[]> {
    const url = this.apiUrl + "/list";

    return this.httpClient.get<Task[]>(url).pipe(
      tap(response => {
        console.log("response", response);
      })
    );
  }

  addTask(task: Task): Observable<Task> {
    const url = this.apiUrl + "/add";

    return this.httpClient.post<Task>(url, task);
  }

  updateTask(updatedTask: Task): Observable<Task> {
    const url = `${ this.apiUrl }/update/${ updatedTask._id }`;

    return this.httpClient.put<Task>(url, updatedTask);
  }

  deleteTask(taskId: string): Observable<TaskResponse> {
    const url = `${ this.apiUrl }/delete/${ taskId }`;

    return this.httpClient.delete<TaskResponse>(url);
  }
}
