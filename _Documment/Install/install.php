<?php
$in = $argv;
/************************** Comand */
$cmd = '';
cab();
if (isset($in[1]))
    {
        $cmd = $in[1];
    }

switch ($cmd)
    {
        case 'register':
            $url = variable_http();            
            if (isset($in[2]))
                {
                    $type = $in[2];
                    $file = 'json/'.$type.'.json';
                    if (file_exists($file))
                        {
                            $txt = file_get_contents($file);
                            $txt = str_replace('$HTTP',$url,$txt);
                            echo $txt;
                            dircheck('.tmp/');
                            dircheck('.tmp/.json');
                            $filed = '.tmp/.json/dataview.json';
                            file_put_contents($filed,$txt);
                            $cmd = "curl -X POST -H 'Content-type: application/json' http://localhost:8080/api/admin/externalTools --upload-file ".$filed;
                            $rst = shell_exec($cmd);
                            //if (file_exists($filed)) { unlink($filed); }
                            echo $rst;
                        } else {
                            echo "\e[00;31m ERROR: configuration type '$type' not found \e[00;00m".cr();
                        }
                } else {
                    help_register();                    
                }
                break;
        default:
            help();
    }

/***************************** CHECK */
function variable_http()
    {
        $url = trim((string)getenv("HTTP_DATAVERSE"));
        if ($url=='')
            {
                help_http_dataverse();
            } else {
                echo "URL: \e[00;34m".$url."\e[00;00m".cr();
            }
        return $url;
    }

/* checa e cria diretorio */
function dircheck($dir) {
    $ok = 0;
    if (is_dir($dir)) { $ok = 1;
    } else {
        mkdir($dir);
        $rlt = fopen($dir . '/index.php', 'w');
        fwrite($rlt, 'acesso restrito');
        fclose($rlt);
    }
    return ($ok);
}    

/***************************** HELP */
function help_http_dataverse()
    {
        echo "\e[00;31m".cr();
        echo '***** WARNING - ATENÇÃO **********************************************'.cr();
        echo 'A variável de ambiente "HTTP_DATAVERSE" não está definida no ambiente'.cr();
        echo 'Defina a URL de instalação do visualizado DataView'.cr();
        echo 'Ex:'.cr();
        echo "\e[00;34m set HTTP_DATAVERSE=http://vitrine.rnp.br/dataview \e[00;31m".cr();
        echo '**********************************************************************'.cr();
        echo "\e[00;00m".cr();

    }
function cr()
    {
        return chr(10).chr(13);
    }
function cab()
    {
        echo '= DataView - Ajuda ========== v0.22.06.26'.cr();
        echo cr();
    }
function help()
    {
        echo 'Funções:'.cr();
        echo '- register CONTENT_TYPE - Registra um visualizado para um tipo de arquivo (Content-type)';
        echo cr();
        echo cr();
    }

function help_register()
    {
        echo "register CONTENT_TYPE".cr();
        echo "=====================".cr();
        echo cr();
        $dir = scandir("json");
        print_r($dir);
    }