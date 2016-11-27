<?php

namespace Controllers;

use Models\University;

class UniversityController extends AbstractController
{
    public function index($param = '')
    {
        $model_name = 'university';
        $model = $this->model($model_name);
        $repository = $this->repository($model_name.'Repository', University::class, 'universities');
        $this->view($model_name, [
            'message' => '',
            'data' => print_r($repository->findAll(), true),
        ]);
    }
}
