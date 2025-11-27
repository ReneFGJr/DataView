import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';

import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { PdfViewerModule } from 'ng2-pdf-viewer';
import { ViewPdfComponent } from './widgat/view-pdf/view-pdf.component';
import { ViewStlComponent } from './widgat/view-stl/view-stl.component';
import { ViewCsvComponent } from './widgat/view-csv/view-csv.component';
import { ViewJpgComponent } from './widgat/view-jpg/view-jpg.component';
import { ViewTxtComponent } from './widgat/view-txt/view-txt.component';
import { ViewComponent } from './page/view/view.component';
import { ViewTabComponent } from './widgat/view-tab/view-tab.component';
import { HttpClientModule } from '@angular/common/http';

@NgModule({
  declarations: [
    AppComponent,
    ViewPdfComponent,
    ViewStlComponent,
    ViewCsvComponent,
    ViewJpgComponent,
    ViewTxtComponent,
    ViewComponent,
    ViewTabComponent,
  ],
  imports: [BrowserModule, AppRoutingModule, PdfViewerModule, HttpClientModule],
  providers: [],
  bootstrap: [AppComponent],
})
export class AppModule {}
