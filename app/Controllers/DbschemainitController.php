<?php

namespace Controllers;

class DbschemainitController extends AbstractController
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
