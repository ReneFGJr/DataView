<h1>Instalador do Visualizador</h1>

No prompt do SO acesse a pasta do DataView

<code>cd /var/www/DataView/_Documment/Install</code>

Execute o instalar:

<code>php install.php</code>

Se estiver tudo certo, receberá a resposta:

<pre>
= DataView - Ajuda
========== v0.22.06.26

Funções:
- register CONTENT_TYPE - Registra um visualizado para um tipo de arquivo (Content-type)
- list - Mostra os visualizadores ativos
- delete ID - Exclui um visualizador ativo
</pre>

<h2>Para registrar um serviço de visualização</h2>

<h3>Visualizador de Imagem JPG</h3>

<code>php install.php register jpg</code>

<pre>
php install.php register png   #Para PNG
php install.php register pdf   #Para PDF
php install.php register ddi   #Para visualizador de dados estatísticos
php install.php register stl   #Para dados geoprocessador
php install.php register tab   #Para dados tabulares
php install.php register txt   #Para arquivos Texto

</pre>
