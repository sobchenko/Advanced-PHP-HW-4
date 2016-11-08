<?php

namespace Controllers;

use Core;

class DberrorController extends Core\Controller implements ControllerInterface
{
    public function index($param = '')
    {
        if ($this->db->error) {
            $view = 'dberror';
            $message = $this->db->error;
            if (strpos($this->db->error, 'Unknown database')) {
                $view = 'dbinit';
                $message = "DB '{$this->db->getDbName()}' doesn'n exist. Would you like to create new db '{$this->db->getDbName()}'?";
            }
            $this->view($view, ['message' => $message]);
        }
    }
}
