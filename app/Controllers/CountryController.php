<?php

namespace Controllers;

use Models\Country;

class CountryController extends AbstractController
{
    public function index($param = '')
    {
        $model_name = 'country';
        $model = $this->model($model_name);
        $repository = $this->repository($model_name.'Repository', Country::class, 'countries');
        $this->view($model_name, [
            'message' => '',
            'data' => print_r($repository->findAll(), true),
        ]);
    }
}
