import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { User } from '../models/user.model';
import { Observable, tap } from 'rxjs';
import { UserResponse } from '../models/user-response.model';

@Injectable({
  providedIn: 'root'
})
export class UserService {
  private apiUrl = 'https://127.0.0.1:8000/api/users';

  constructor(private httpClient: HttpClient) {}

  getUsers(): Observable<User[]> {
    const url = this.apiUrl + "/list";

    return this.httpClient.get<User[]>(url).pipe(
      tap(response => {
        console.log("response", response);
      })
    );
  }

  addUser(User: User): Observable<User> {
    const url = this.apiUrl + "/add";

    return this.httpClient.post<User>(url, User);
  }

  updateUser(updatedUser: User): Observable<User> {
    const url = `${ this.apiUrl }/update/${ updatedUser._id }`;

    return this.httpClient.put<User>(url, updatedUser);
  }

  deleteUser(UserId: string): Observable<UserResponse> {
    const url = `${ this.apiUrl }/delete/${ UserId }`;

    return this.httpClient.delete<UserResponse>(url);
  }
}
