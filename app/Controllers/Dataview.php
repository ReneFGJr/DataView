<?php

namespace App\Controllers;

use App\Controllers\BaseController;

helper(['boostrap','url','form']);
$this->session = \Config\Services::session();
$language = \Config\Services::language();

class Dataview extends BaseController
{
    public function index()
    {
        $DataViewer = new \App\Models\DataViewer();
        $tela = $DataViewer->index();
        return $tela;
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
