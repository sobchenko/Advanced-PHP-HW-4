<?php

namespace Core;

abstract class Controller
{
    protected $loader, $twig, $db, $baseUrl;

    public function __construct(DataStorage $dataStorage, $baseUrl, $cache, $debug)
    {
        $this->db = $dataStorage;
        $this->baseUrl = $baseUrl;
        $this->loader = new \Twig_Loader_Filesystem('../app/views/');
        $this->twig = new \Twig_Environment($this->loader, ['cache' => $cache, 'debug' => $debug]);
        if ($debug) {
            $this->twig->addExtension(new \Twig_Extension_Debug());
        }
    }

    protected function model($model)
    {
        $init_model = 'Models\\'.ucfirst($model);

        return new $init_model();
    }

    protected function view($view, $data = [])
    {
        $data['baseUrl'] = $this->baseUrl;
        echo $this->twig->render($view.'.twig', $data);
    }
}
