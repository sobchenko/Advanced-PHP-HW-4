<?php

class App
{
    protected $controller = 'HomeController';
    protected $method = 'index';
    protected $params = [];

    public function __construct($environment)
    {
        $url = $this->parseUrl();

        if (file_exists('../app/Controllers/'.ucfirst($url[0]).'Controller.php')) {
            $this->controller = ucfirst($url[0]).'Controller';
            unset($url[0]);
        }

        $cache = false;
        $debug = true;
        if ($environment == 'live') {
            $cache = '../app/cache/';
            $debug = false;
        }

        $ctrl = 'Controllers\\'.$this->controller;
        $this->controller = new $ctrl($cache, $debug);

        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }
        $this->params = $url ? array_values($url) : [];
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    protected function parseUrl()
    {
        if (isset($_GET['url'])) {
            return explode('/', filter_var(strtolower(rtrim($_GET['url'], '/')), FILTER_SANITIZE_URL));
        }
    }
}
