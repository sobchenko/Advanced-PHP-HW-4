<?php

namespace Controllers;

use Core;

class HomeController extends Core\Controller implements ControllerInterface
{
    public function index($param = '')
    {
//        $university = $this->model(ucfirst($param));
        $this->view('home', [
            'message' => ''
        ]);
    }
}
