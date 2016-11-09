<?php

namespace Controllers;

class DbsampledataController extends AbstractController
{
    public function index($param = '')
    {
        $view = 'sample_data';
        $message = '';
        $this->view($view, [
            'message' => $message,
            'generate_section' => 1,
            'items_number' => 20
        ]);
    }

    public function generate($param = '')
    {
        $view = 'sample_data';
        $message = '';

        $items_number = $_POST['items_number'] ? $_POST['items_number'] : 20;



        $this->view($view, [
            'message' => $message,
            'result' => 1
        ]);

    }
}
