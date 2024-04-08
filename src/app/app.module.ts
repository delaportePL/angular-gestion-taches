import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';

import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { TaskListComponent } from './features/task-list/task-list.component';
import { TaskDetailsComponent } from './features/task-details/task-details.component';
import { TaskMinimizedComponent } from './features/task-minimized/task-minimized.component';

@NgModule({
  declarations: [
    AppComponent,
    TaskListComponent,
    TaskDetailsComponent,
    TaskMinimizedComponent
  ],
  imports: [
    BrowserModule,
    AppRoutingModule
  ],
  providers: [],
  bootstrap: [AppComponent]
})
export class AppModule { }
