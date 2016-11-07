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
        var_dump($name);
        $university = $this->model(ucfirst($name));
        $university->name = 'CNU';
        $this->view('index', ['name' => $university->name]);
    }
}
