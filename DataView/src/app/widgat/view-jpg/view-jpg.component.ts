import { Component } from '@angular/core';

@Component({
  selector: 'app-view-jpg',
  templateUrl: './view-jpg.component.html',
  styleUrls: ['./view-jpg.component.scss'],
})
export class ViewJpgComponent {
  imageSrc: string;

  constructor() {
    this.imageSrc = 'assets/img/mulher-biblioteconomia.webp'
  }

  ngOnInit(): void {}
}
