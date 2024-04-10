import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { Task } from '../models/task.model';
import { Project } from '../models/project.model';
import { TaskState } from '../enums/task-state.enum';
import { TaskCategory } from '../enums/task-category.enum';
import { TaskService } from '../services/task.service';

@Component({
  selector: 'app-task-form',
  templateUrl: './task-form.component.html',
  styleUrls: ['./task-form.component.css']
})
export class TaskFormComponent implements OnInit {
  taskForm!: FormGroup;
  taskStateEnum = TaskState;
  taskCategoryEnum = TaskCategory;
  
  projects: Project[] = [
    { projectId: "BRP", label: "Automatisation des tests", tasks: [] },
    { projectId: "UX", label:"Amélioration UX", tasks: []}
  ]

  constructor(
    private fb: FormBuilder,
    private taskService: TaskService
  ) { }

  ngOnInit(): void {
    this.initForm();
  }

  initForm(): void {
    this.taskForm = this.fb.group({
      // idTask: ['', Validators.required],
      title: ['', Validators.required],
      projectId: ['', Validators.required],
      state: ['', Validators.required],
      category: ['', Validators.required],
      description: [''],
      points: ['', Validators.required],
      // creatorUserId: ['', Validators.required],
      // assignedUserId: [''],
      // creationDate: ['', Validators.required],
      // modificationDate: ['', Validators.required]
    });

    console.log(this.taskForm)
  }

  isFieldValid(fieldName: string) {
    if(this.taskForm.get(fieldName)?.invalid && 
      (this.taskForm.get(fieldName)?.dirty || this.taskForm.get(fieldName)?.touched)) {
      return false;
    } else {
      return true;
    }
  }

  returnFormError(fieldName: string) {
    const errors = this.taskForm.get(fieldName)?.errors;
    if(errors) {
      if(errors['required'] == true) {
        return `Le champs ${fieldName} est requis !`;
      }
    }
    return `Erreur avec le champs ${fieldName} !`;
  }

  onSubmit(): void {
    if (this.taskForm.valid) {
      const newTask: Task = this.taskForm.value;
      // Perform any additional actions, such as sending the task to the backend
      this.taskService.addTask(newTask).subscribe(res => {
        console.log(res);
      });
    } else {
      // Handle form validation errors
    }
  }
}
