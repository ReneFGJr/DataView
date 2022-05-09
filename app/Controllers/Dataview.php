<?php

namespace App\Controllers;

use App\Controllers\BaseController;

helper(['boostrap','url','form','sisdoc_form']);
$this->session = \Config\Services::session();
$language = \Config\Services::language();


define("PATH",'http://dataview/public/index.php/');
define("URL",'http://dataview/public/');
define("MODULE",'dataview');

class Dataview extends BaseController
{
    public function index()
    {
        $DataViewer = new \App\Models\DataViewer();
        $tela = $DataViewer->index();
        return $tela;
    }

    function file($id='')
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

    function help($d1='',$d2='',$d3='',$d4='')
        {
            $sx = h('Help');
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

    function view($content='',$d1='',$d2='',$d3='')
        {
            $sx = '';
            switch($content)
                {
                    case 'form':
                        $DataViewer = new \App\Models\DataViewer();
                        $sx .= $this->cab();
                        $sa = $DataViewer->formView();
                        $sx .= bs(bsc($sa,12));
                        break;

                    case 'pdf':
                    $PDF = new \App\Models\Preview\PDF();
                    $sx = $PDF->view($d1,$d2,$d3);
                    break;

                    default:
                    break;
                }
            return $sx;
        }

    function test($d1='',$d2='',$d3='')
        {

        } 

    function admin($d1='',$d2='',$d3='')
        {
            $DataViewerAdmin = new \App\Models\DataViewerAdmin();
            $tela = '';
            $tela .= '<div class="container">';
            $tela .= '<div class="row">';
            $tela .= '<h1>ADMIN</h1>';
            $tela .= '</div>';
            $tela .= '</div>';
            $tela .= $DataViewerAdmin->index($d1,$d2,$d3);
            return $tela;
        }
}
