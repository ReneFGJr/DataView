# DataView
 
Plug-in para visualização de dados para o dataverse

Para ativação consulte: <a href="APACHE.md">APACHE.md</a>

<h2>Para instalação</h2>

Renomear o arquivo "env" para ".env"

Editar o arquivo .env
Editando os parametros para produção ou para desenvolvimento

CI_ENVIRONMENT = production
ou
CI_ENVIRONMENT = development

Definir a URL do site
app.baseURL = 'http://pocdadosabertos.inep.rnp.br/dataview/'


Arquivo brapci_dataview.json
<code>

 "displayName": "Brapci DataView - Dataverse",
 "description": "Visualizado de dados",
 "toolName": "brapci_dataview",
 "scope": "file",
 "types": [
   "preview"
 ],
 "toolUrl": "https://pocdadosabertos.inep.rnp.br/dataview/",
 "contentType": "text/tab-separated-values",
 "toolParameters": {
   "queryParameters": [
        {"fileid": "{fileId}"},
        {"siteUrl":"{siteUrl}"},
        {"PID": "{datasetPid}"},
        {"key": "{apiToken}"},
        {"datasetId": "{datasetId}"},
        {"localeCode":"{localeCode}"}
   ]
 }
}
</code>

<h2>Mostra Visualizadores Ativos</h2>
<code>curl http://localhost:8080/api/admin/externalTools | jq '.'</code>

<h2>Registrar Visualizador</h2>
<code>curl -X POST -H 'Content-type: application/json' http://localhost:8080/api/admin/externalTools --upload-file brapci_dataview.json</code>

<h2>Deletar Visualizador</h2>
<code>curl -X DELETE http://localhost:8080/api/admin/externalTools/$1</code>

