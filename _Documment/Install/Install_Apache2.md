

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