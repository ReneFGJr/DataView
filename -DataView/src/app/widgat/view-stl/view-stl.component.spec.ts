import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ViewStlComponent } from './view-stl.component';

describe('ViewStlComponent', () => {
  let component: ViewStlComponent;
  let fixture: ComponentFixture<ViewStlComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      declarations: [ViewStlComponent]
    });
    fixture = TestBed.createComponent(ViewStlComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
