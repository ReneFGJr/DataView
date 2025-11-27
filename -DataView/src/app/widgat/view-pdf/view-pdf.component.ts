import { Component } from '@angular/core';

@Component({
  selector: 'app-view-pdf',
  templateUrl: './view-pdf.component.html',
  styleUrls: ['./view-pdf.component.scss'],
})
export class ViewPdfComponent {
  pdfSrc = '/assets/sample.pdf'; // Caminho do arquivo PDF
  constructor() {}
}
