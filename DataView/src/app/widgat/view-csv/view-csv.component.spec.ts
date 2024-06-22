import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ViewCsvComponent } from './view-csv.component';

describe('ViewCsvComponent', () => {
  let component: ViewCsvComponent;
  let fixture: ComponentFixture<ViewCsvComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      declarations: [ViewCsvComponent]
    });
    fixture = TestBed.createComponent(ViewCsvComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
