<?php

namespace Controllers;

class DbcreateController extends AbstractController
{
    public function index($param = '')
    {
        $this->db->createDb();
        $view = 'home';
        $message = $this->db->message;
        if ($this->db->error) {
            $view = 'dberror';
            $message = $this->db->error;
        }
        $this->view($view, ['message' => $message]);
    }
}
