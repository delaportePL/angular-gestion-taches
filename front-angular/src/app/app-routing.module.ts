import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { PageNotFoundComponent } from './features/page-not-found/page-not-found.component';
import { TaskListOverlayComponent } from './features/task-list-overlay/task-list-overlay.component';
import { TaskDetailsComponent } from './features/task-details/task-details.component';
import { TaskFormComponent } from './features/task-form/task-form.component';

const routes: Routes = [
  { 
    path: '', component: TaskListOverlayComponent, pathMatch: 'full',
    children: [
      { path:'task/:id', component: TaskDetailsComponent}]
  },
  { path: 'ajouter-tache', component: TaskFormComponent },
  { path: '**', component: PageNotFoundComponent,  pathMatch: 'full' },  // Route for a 404 page
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
