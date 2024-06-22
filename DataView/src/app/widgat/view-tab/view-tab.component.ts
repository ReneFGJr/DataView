import { Component } from '@angular/core';
import { DataverseService } from 'src/app/service/dataverse.service';

@Component({
  selector: 'app-view-tab',
  templateUrl: './view-tab.component.html',
  styleUrls: ['./view-tab.component.scss'],
})
export class ViewTabComponent {
  data: string[][] = [];

  constructor(private dataverseService: DataverseService) {}

  ngOnInit(): void {
    this.loadTabFile();
  }

  loadTabFile(): void {
    this.dataverseService.getTabFile().subscribe((response) => {
      this.parseTabFile(response);
    });
  }

  parseTabFile(fileContent: string): void {
    const lines = fileContent.split('\n');
    this.data = lines.map((line) => line.split('\t'));
  }
}
