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

@NgModule({
  declarations: [
    AppComponent,
    TaskListComponent,
    TaskDetailsComponent,
    TaskMinimizedComponent,
    PageNotFoundComponent,
    TaskListOverlayComponent,
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    HttpClientModule
  ],
  providers: [],
  bootstrap: [AppComponent]
})
export class AppModule { }
