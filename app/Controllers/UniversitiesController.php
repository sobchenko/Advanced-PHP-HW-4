<?php

namespace Controllers;

class UniversitiesController extends AbstractController
{
    public function index($param = '')
    {
        $model_name = $param ? $param : 'universities';
        $model = $this->model(ucfirst($model_name));
        $this->view($model_name, [
            'message' => '',
            'data' => print_r($model->findAll(), true)
        ]);
    }
}
