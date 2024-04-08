import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { TaskListComponent } from './features/task-list/task-list.component';
import { PageNotFoundComponent } from './features/page-not-found/page-not-found.component';
import { TaskListOverlayComponent } from './features/task-list-overlay/task-list-overlay.component';

const routes: Routes = [
  // { path: '', component: AppComponent},
  { path: 'liste-taches', component: TaskListOverlayComponent },
  { path: '**', component: PageNotFoundComponent },  // Wildcard route for a 404 page
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
