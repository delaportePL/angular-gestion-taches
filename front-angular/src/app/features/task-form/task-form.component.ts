import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { Task } from '../models/task.model';
import { Project } from '../models/project.model';
import { TaskState } from '../enums/task-state.enum';
import { TaskCategory } from '../enums/task-category.enum';
import { TaskService } from '../services/task.service';
import { UserService } from '../services/user.service';
import { User } from '../models/user.model';
import { Router } from '@angular/router';

@Component({
  selector: 'app-task-form',
  templateUrl: './task-form.component.html',
  styleUrls: ['./task-form.component.css']
})
export class TaskFormComponent implements OnInit {
  taskForm!: FormGroup;
  taskStateEnum = TaskState;
  taskCategoryEnum = TaskCategory;

  users!: User[];
  selectedUser!: User;
  
  projects: Project[] = [
    { projectId: "BRP", label: "Automatisation des tests", tasks: [] },
    { projectId: "UX", label:"Amélioration UX", tasks: []}
  ]

  constructor(
    private fb: FormBuilder,
    private taskService: TaskService,
    private userService: UserService,
    private router: Router
  ) { }

  ngOnInit(): void {
    this.initForm();
    this.userService.getUsers().subscribe(users => {
      this.users = users;
      this.selectedUser=users[0];
      this.taskForm.get('creatorUserId')?.setValue(this.selectedUser._id.$oid);
    })
  }

  initForm(): void {
    this.taskForm = this.fb.group({
      title: ['', Validators.required],
      projectId: ['', Validators.required],
      state: ['', Validators.required],
      category: ['', Validators.required],
      description: [''],
      points: ['', Validators.required],
      creatorUserId: ['', Validators.required],
      assignedUserId: [''],
      // creationDate: ['', Validators.required],
      // modificationDate: ['', Validators.required]
    });

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
      console.log(newTask)
      this.taskService.addTask(newTask).subscribe(res => {
        console.log(res);
        this.router.navigateByUrl('');
      });
    } else {
    }
  }
}
