import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ViewTxtComponent } from './view-txt.component';

describe('ViewTxtComponent', () => {
  let component: ViewTxtComponent;
  let fixture: ComponentFixture<ViewTxtComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      declarations: [ViewTxtComponent]
    });
    fixture = TestBed.createComponent(ViewTxtComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
