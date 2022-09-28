# DataView

<h1>Introdução</h1>

O DataView é uma ferramenta externa que se integra ao Dataverse possibilitando a visualização de alguns arquivos sem a necessidade de baixá-los.
<br/>
Para instalar é necessário ter acesso ao SO.

<br/>
Para ativação consulte: <a href="APACHE.md">APACHE.md</a>
<br/>
<h2>Para instalação</h2>

Para instalação você pode baixa diretamente o arquivo em ZIP pelo <a href="https://github.com/ReneFGJr/DataView/archive/refs/heads/main.zip">ZIP do Github</a> ou clonar o repositório em sua instalação.

<code>
    cd /var/www
    git close https://github.com/ReneFGJr/DataView.git
</code>
<h3></h3>

<h2>Para configuração</h2>

Renomear o arquivo "env" para ".env"

Editar o arquivo .env
Editando os parametros para produção ou para desenvolvimento

CI_ENVIRONMENT = production
ou
CI_ENVIRONMENT = development

Definir a URL do site
app.baseURL = 'http://pocdadosabertos.inep.rnp.br/dataview/'

Para instalar, consultar ou remover visualizadores consulte:
<a href="_Documment/Install/Install.md">Instalador (Aplicativo)</a>

Para criar um PATH no Apache2 para o diretorio /dataview/
<a href="_Documment/Install/Install_Apache2.md">Instalador Apache2 (Web)</a>