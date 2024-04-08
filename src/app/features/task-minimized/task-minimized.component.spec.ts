import { ComponentFixture, TestBed } from '@angular/core/testing';

import { TaskMinimizedComponent } from './task-minimized.component';

describe('TaskMinimizedComponent', () => {
  let component: TaskMinimizedComponent;
  let fixture: ComponentFixture<TaskMinimizedComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      declarations: [TaskMinimizedComponent]
    });
    fixture = TestBed.createComponent(TaskMinimizedComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
