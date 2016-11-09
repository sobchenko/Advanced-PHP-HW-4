<?php

namespace Controllers;

class HomeController extends AbstractController
{
    public function index($param = '')
    {
        $message = count($this->db->tableList) ? '' : 'DB schema doesn\'t exist. Create DB schema!';
        $this->view('home', [
            'message' => $message,
        ]);
    }
}
