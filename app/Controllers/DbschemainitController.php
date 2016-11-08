<?php

namespace Controllers;

use Core;

class DbschemainitController extends Core\Controller implements ControllerInterface
{
    public function index($param = '')
    {
        if (count($this->db->tableList)) {
            $message = 'Db schema already exists.';
        } else {
            $this->db->initDbSchema();
            $message = '';
        }
        $this->view('home', ['message' => $message]);
    }
}
