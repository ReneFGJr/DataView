# DataView

<h1>Requisistos</h1>
<ul>
<li>PHP 7.4 ou superiro</li>
</ul>

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
    cd /var/www #ou o diretório que preferir<br/>
    git clone https://github.com/ReneFGJr/DataView.git
</code>
<h3></h3>

<h2>Para configuração</h2>

Acesse a pasta de instalação ex:
<code>
 cd /var/www/DataView
</code>

Criar uma cópia do arquivo "env" para configurações ".env"
<code>cp env .env</code>

Editar o arquivo .env
<code>nano .env</code>

Editando os parametros para produção ou para desenvolvimento

CI_ENVIRONMENT = production
ou
CI_ENVIRONMENT = development

Definir a URL do site
app.baseURL = 'http://pocdadosabertos.inep.rnp.br/dataview/'

Libere acesso a escrita no diretorio abaixo para o usuário web

chown www-data:www-data /var/www/DataView/writable/ -R
chown www-data:www-data /var/www/DataView/.tmp/ -R

Para instalar, consultar ou remover visualizadores consulte:
<a href="_Documment/Install/Install.md">Instalador (Aplicativo)</a>

Para criar um PATH no Apache2 para o diretorio /dataview/
<a href="_Documment/Install/Install_Apache2.md">Instalador Apache2 (Web)</a>