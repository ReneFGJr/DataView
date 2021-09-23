<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Dataview extends BaseController
{
    public function index()
    {
        echo '<pre>';
        print_r($_POST);
        echo '<hr>';
        print_r($_GET);
    }
}
