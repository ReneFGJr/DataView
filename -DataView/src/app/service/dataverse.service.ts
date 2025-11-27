import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root',
})
export class DataverseService {
  private siteUrl = 'https://cedapdados.ufrgs.br'; // Substitua pelo seu site URL
  private PID = 'hdl:20.500.11959/dt/25/3'; // Substitua pelo seu PID
  private version = '2.0'; // Substitua pela versão
  private key = ''; // Substitua pela sua chave de API
  private datasetId = ''; // Substitua pelo seu ID do conjunto de dados
  private fileid = ''; // Substitua pelo seu ID do arquivo

  constructor(private http: HttpClient) {}

  getTabFile(): Observable<any> {
    //let url = `${this.siteUrl}/file.xhtml?persistentId=${this.PID}&version=${this.version}`;
    let url = `${this.siteUrl}/file.xhtml?fileId=97&version=2.0#`;
    console.log(url);

    const headers = new HttpHeaders().set('X-Dataverse-key', this.key);
    return this.http.get(url, { headers, responseType: 'text' });
  }
}
