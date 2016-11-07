<?php

namespace Core;

use Models;

class Controller
{
    protected function model($model)
    {
        require_once '../app/Models/'.ucfirst($model).'.php';
        $init_model = 'Models\\'.ucfirst($model);

        return new $init_model();
    }
}
