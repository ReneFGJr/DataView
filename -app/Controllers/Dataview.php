<?php

namespace App\Controllers;

use App\Controllers\BaseController;

helper(['boostrap', 'url', 'form', 'sisdoc_forms']);
$this->session = \Config\Services::session();
$language = \Config\Services::language();


define("PATH", getenv("app.baseURL") . getenv("app.baseURLprefix"));
define("URL", getenv("app.baseURL"));
define("MODULE", 'dataview');

class Dataview extends BaseController
{
    public function content($txt='')
    {
        $DataViewer = new \App\Models\DataViewer();
        //$sx = $DataViewer->index();
        $sx = view('header/header');
        $sx .=  '<div class="container-fluid">';
        $sx .=  '<div class="row">';
        $sx .=  '<div class="col-6 p-5 text-center h-100 d-inline-block" style="background-color: #830705;">';
        $sx .=  '<img src="'.getenv('app.baseURL').'/img/logo/logo_dataview-pb.png" class="mt-5 mb-5" style="width: 300px;">';
        $sx .=  '<div style="height: 1024px;"></div>';
        $sx .=  '</div>';
        $sx .=  '<div class="col-6 p-1 text-end" style="background-color: #FFF;">';
        $sx .=  "v0.22.11.17<hr>";

        $sx .= ' '.$txt;

        $sx .=  '</div>';
        $sx .=  '</div>';
        $sx .=  '</div>';
        $sx .=  '</div>';
        return $sx;
    }

    function index()
        {
            echo "OK";
            exit;
            $sx = $this->content('ONLINE');
            return $sx;
        }

    function open()
        {
        $sx = '';
        $OPEN = new \App\Models\Forms\DOI();
        $Codebook = new \App\Models\Preview\Codebook();

        $url = get("url");
        $doi = get("doi");
        if ((substr($url,0,4) == 'http') and ($doi != ''))
            {
                $sx .= $Codebook->show($url,$doi);
            } else {
                $sx .= $this->content($OPEN->form());
            }
        return $sx;
        }

    function clear()
        {
            $Cache = new \App\Models\IO\Cache();
            $nr = $Cache->clear();
            $sx = $this->content('<center>'.bsmessage($nr. ' cache cleared.'. '</center>',1));
            return $sx;
        }

    function file($id = '')
    {
        $FILE = new \App\Models\Preview\File();
        $this->file($id);
    }

    function stf($id = '')
    {
        $FILE = new \App\Models\Preview\File();
        $this->file($id);
    }

    function pdf()
    {
        $PDF = new \App\Models\Preview\PDF();
        $PDF->view();
        return "Erro open file";
    }

    function help($d1 = '', $d2 = '', $d3 = '', $d4 = '')
    {
        $sx = view('header/header');
        $sx .= bs(bsc(h('Help'), 12));
        $sx .= bs(bsc('<a href="https://github.com/ReneFGJr/DataView">See Documentation</a>'));
        return $sx;
    }

    function cab()
    {
        $sx = '';
        $sx .= '
            <!-- CSS only -->
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
            <!-- JavaScript Bundle with Popper -->
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
            ';
        return $sx;
    }

    function view($content = '', $d1 = '', $d2 = '', $d3 = '')
    {
        $sx = '';
        switch ($content) {

            case 'jpg':
                $DataViewer = new \App\Models\Preview\Image();
                echo $DataViewer->index();
                break;

            case 'png':
                $DataViewer = new \App\Models\Preview\Image();
                echo $DataViewer->index();
                break;

            case 'tab':
                $DataViewer = new \App\Models\Preview\TAB();
                echo $DataViewer->index();
                break;

            case 'txt':
                $DataViewer = new \App\Models\Preview\TXT();
                echo $DataViewer->index();
                break;

            case 'ddi':
                $DataViewer = new \App\Models\Preview\DDI();
                echo $DataViewer->index();
                break;

            case 'stl':
                $DataViewer = new \App\Models\Preview\STL();
                echo $DataViewer->index();
                break;

            case 'form':
                $DataViewer = new \App\Models\DataViewer();
                $sx .= $this->cab();
                $sa = $DataViewer->formView();
                $sx .= bs(bsc($sa, 12));
                break;

            case 'pdf':
                $PDF = new \App\Models\Preview\PDF();
                $sx = $PDF->view($d1, $d2, $d3);
                break;

            default:
                $sx = '';
                $sx .=  '<div class="container-fluid">';
                $sx .=  '<div class="row">';
                $sx .=  '<div class="col-6" style="background-color: #830705;">';
                $sx .=  '<img src="/img/logo/logo_dataview-pb.png" style="width: 50%">';
                $sx .=  '</div>';
                $sx .=  '<div class="col-6" style="background-color: #830705;">';
                $sx .=  "XXXXXXXXXXXX";
                $sx .=  '</div>';
                $sx .=  '</div>';
                $sx .=  '</div>';
                $sx .=  '<pre>';
                $sx .=  h($content);
                return $sx;
                break;
        }
        return $sx;
    }

    function test($d1 = '', $d2 = '', $d3 = '')
    {
    }

    function admin($d1 = '', $d2 = '', $d3 = '')
    {
        $DataViewerAdmin = new \App\Models\DataViewerAdmin();
        $tela = '';
        $tela .= '<div class="container">';
        $tela .= '<div class="row">';
        $tela .= '<h1>ADMIN</h1>';
        $tela .= '</div>';
        $tela .= '</div>';
        $tela .= $DataViewerAdmin->index($d1, $d2, $d3);
        return $tela;
    }
}