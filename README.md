# Para instalar

## Ajuste no Apache2
Inclua o proxy
<code>
    ProxyPass /dataview http://127.0.0.1:81/dataview/
    ProxyPassReverse /dataview https://127.0.0.1:81/dataview
</code>

Alter o arquivo para incluir a porta 81
<code>
    pico /etc/apache2/ports.conf

    Listen 81
</code>

# Link do diretorio
Crie um link em var/www para o DataView
<code>
mkdir /var/www/dataview
ln -s /home/dataverse/DataView/ /var/www/
chown www-data:www-data /home/dataverse/DataView/ -R
</code>

# TIFF Image
pip install pillow