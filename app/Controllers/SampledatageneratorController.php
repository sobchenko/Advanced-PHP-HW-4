<?php

namespace Controllers;

class SampledatageneratorController extends AbstractController
{
    public function index($param = '')
    {
        $view = 'sample_data';
        $message = '';
        $this->view($view, [
            'message' => $message,
            'generate_section' => 1,
            'items_number' => 20,
        ]);
    }

    public function generate($param = '')
    {
        $itemNumbers = $_POST['items_number'] ? $_POST['items_number'] : 20;
        $repository = $this->repository('MySQLSampleDataGeneratorRepository');
        $repository->generateAll($itemNumbers);
        $view = 'sample_data';
        $message = 'OK!';
        $this->view($view, [
            'message' => $message,
            'result' => 1,
        ]);
    }
}
