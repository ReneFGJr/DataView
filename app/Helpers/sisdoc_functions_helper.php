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

function get($key, $default = null)
{
    if (isset($_GET[$key])) {
        return $_GET[$key];
    } else {
        if (isset($_POST[$key])) {
            return $_POST[$key];
        } else {
            return $default;
        }
    }
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

function displayDV($text)
{
    $rsp = '';
    if (is_array($text)) {
        return "";
        foreach ($text as $t) {
            if (is_array($t)) {
                foreach($t as $k1=>$v1)
                    {
                        $rsp .= $k1 . ': ' . displayDV($v1) . '<br>';
                    }
            } else {
                $rsp .=  str_replace('<', '&lt;', str_replace('>', '&gt;', $t)) . '<br>';
            }
        }
    }
    //$rsp = str_replace('<', '&lt;', $rsp);
    //$rsp = str_replace('>', '&gt;', $rsp);
    return $rsp;
}