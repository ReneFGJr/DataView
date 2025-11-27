import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { ViewComponent } from './page/view/view.component';
import { ViewJpgComponent } from './widgat/view-jpg/view-jpg.component';
import { ViewTabComponent } from './widgat/view-tab/view-tab.component';

const routes: Routes = [
  { path: '', component: ViewComponent, },
  { path: 'webp', component: ViewJpgComponent },
  { path: 'tab', component: ViewTabComponent }
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
