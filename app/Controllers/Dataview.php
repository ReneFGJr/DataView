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
}
