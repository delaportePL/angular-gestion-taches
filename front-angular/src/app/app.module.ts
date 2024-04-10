import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';
import { HttpClientModule } from '@angular/common/http';

import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { TaskListComponent } from './features/task-list/task-list.component';
import { TaskDetailsComponent } from './features/task-details/task-details.component';
import { TaskMinimizedComponent } from './features/task-minimized/task-minimized.component';
import { PageNotFoundComponent } from './features/page-not-found/page-not-found.component';
import { TaskListOverlayComponent } from './features/task-list-overlay/task-list-overlay.component';
import { ReactiveFormsModule } from '@angular/forms';
import { TaskFormComponent } from './features/task-form/task-form.component';
import { UserListComponent } from './features/user-list/user-list.component';

@NgModule({
  declarations: [
    AppComponent,
    TaskListComponent,
    TaskDetailsComponent,
    TaskMinimizedComponent,
    PageNotFoundComponent,
    TaskListOverlayComponent,
    TaskFormComponent,
    UserListComponent,
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    HttpClientModule,
    ReactiveFormsModule
  ],
  providers: [],
  bootstrap: [AppComponent]
})
export class AppModule { }
