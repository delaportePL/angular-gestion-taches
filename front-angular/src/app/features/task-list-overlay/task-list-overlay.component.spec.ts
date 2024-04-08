import { ComponentFixture, TestBed } from '@angular/core/testing';

import { TaskListOverlayComponent } from './task-list-overlay.component';

describe('TaskListOverlayComponent', () => {
  let component: TaskListOverlayComponent;
  let fixture: ComponentFixture<TaskListOverlayComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      declarations: [TaskListOverlayComponent]
    });
    fixture = TestBed.createComponent(TaskListOverlayComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
