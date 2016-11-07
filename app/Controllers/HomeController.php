<?php

namespace Controllers;

use Core;

class HomeController extends Core\Controller
{
    public function __construct()
    {
    }

    public function index($name = '')
    {
        $university = $this->model(ucfirst($name));
        $university->name = 'CNU';
        print_r($university);
    }
}
