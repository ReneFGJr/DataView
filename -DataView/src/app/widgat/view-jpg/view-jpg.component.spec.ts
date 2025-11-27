import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ViewJpgComponent } from './view-jpg.component';

describe('ViewJpgComponent', () => {
  let component: ViewJpgComponent;
  let fixture: ComponentFixture<ViewJpgComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      declarations: [ViewJpgComponent]
    });
    fixture = TestBed.createComponent(ViewJpgComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
