<?php
/* checa e cria diretorio */
function dircheck($dir)
{
    $ok = 0;
    if (is_dir($dir)) {
        $ok = 1;
    } else {
        mkdir($dir);
        $rlt = fopen($dir . '/index.php', 'w');
        fwrite($rlt, 'acesso restrito');
        fclose($rlt);
    }
    return ($ok);
}

function pre($dt, $force = true)
{
    echo '<pre>';
    print_r($dt);
    echo '</pre>';
    if ($force) {
        exit;
    }
}