<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Dataview extends BaseController
{
    public function index()
    {
        $DataViewer = new \App\Models\DataViewer();
        $tela = $DataViewer->index();
        return $tela;
    }
}
