<?php

namespace Controllers;

use Core;

class HomeController extends Core\Controller implements ControllerInterface
{
    public function index($name = '')
    {
        $university = $this->model(ucfirst($name));
        $university->name = 'CNU';
        $this->view('home', ['name' => $university->name]);
    }
}
