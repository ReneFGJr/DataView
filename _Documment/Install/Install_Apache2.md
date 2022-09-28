

Existem duas maneiras de redirecionar os acessos para o aplicadir do DataView
<h2>Primeira Forma</h2>
A primeira é criar uma regra de excessão no Apache, encaminhando para um diretorio quando acessado pelo endereço https://[SEU DNS]/dataview

Os arquivos de configuração estão em
<code> cd /etc/apache2/sites-enabled</code>

Crie um regra para o Apache ignorar o Path /dataview

<code>ProxyPass /dataview !</code>

Crie um alias para o Apache redirecionar para a pasta do DataView
<code>Alias "/dataview/" "/var/www/DataView/public/"</code>

Configure o tipo de acesso ao diretório
<br/>
<pre>
        &lt;Directory "/var/www/DataView/public/">
                Options Indexes FollowSymLinks MultiViews
                AllowOverride None
                Order allow,deny
                allow from all
                Require all granted
        &lt;/Directory>
</pre>


<h2>Segunda Forma</h2>
Insira em seu arquivo default do Apache2 (porta 443) o redirecionador via Proxy para a porta 8010
<pre>
       &lt;Location /dataview>
               ProxyPass http://localhost:8010
               SetEnv force-proxy-request-1.0 1
               SetEnv proxy-nokeepalive 1
       &lt;/Location>
</pre>

Crie o arquivo no diretório /etc/apache2/sites-avaliable/
<pre>pico /etc/apache2/sites-available/dataview.conf</pre>
Com o conteúdo
<pre>
    &lt;VirtualHost *:8010>
        ServerAdmin renefgj@gmail.com
        ServerName lattesdata.cnpq.br
        DocumentRoot /data/DataView/PHP/public
        &lt;Directory "/data/DataView/PHP/public">
            Require all granted
        &lt;/Directory>
    RewriteEngine on
    RewriteCond %{SERVER_NAME} =lattesdata.cnpq.br
    RewriteRule ^ https://%{SERVER_NAME}%{REQUEST_URI} [END,NE,R=permanent]
    &lt;/VirtualHost>
</pre>

Inclua no arquivo de Portas do Apache2 a liberação de acesso
<pre>
Listen 8010
</pre>

Habilite o servidor no apache2 e reinicialize o Apache2
<pre>
a2ensite dataview.conf
service apache2 restart
</pre>

Dentro do diretório do DataView copie e customize o arquivo de configuração
<pre>
cd /data/DataView
cp env .env
pico .env
</pre>

Libere o acesso ao usuário apache2 para os arquivos
<pre>
chown www-data /data/DataView/ -R
</pre>