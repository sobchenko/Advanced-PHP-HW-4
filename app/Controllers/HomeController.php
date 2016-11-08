<?php

namespace Controllers;

use Core;

class HomeController extends Core\Controller implements ControllerInterface
{
    public function index($param = '')
    {
        $message = count($this->db->tableList) ? '' : 'DB schema doesn\'t exist. Create DB schema!';
        $this->view('home', [
            'message' => $message,
        ]);
    }
}
