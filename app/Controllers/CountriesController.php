<?php

namespace Controllers;

class CountriesController extends AbstractController
{
    public function index($param = '')
    {
        $model_name = $param ? $param : 'countries';
        $model = $this->model(ucfirst($model_name));
        $this->view($model_name, [
            'message' => '',
            'data' => print_r($model->findAll(), true),
        ]);
    }
}
