<?php

namespace Core;

class Controller
{
    protected $loader, $twig;

    public function __construct($cache, $debug)
    {
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
        echo $this->twig->render($view.'.twig', $data);
    }
}
