<?php

class App
{
    protected $baseUrl;
    protected $controller = 'HomeController';
    protected $method = 'index';
    protected $params = [];

    public function __construct($environment, \Core\DataStorage $db)
    {
        $url = isset($_GET['url']) ? $_GET['url'] : null;
        $url = ($db->error && ($url != 'dbcreate')) ? 'dberror/index' : $url;

        $url = $this->parseUrl($url);
        $this->setBaseUrl();

        if (isset($url[0]) && file_exists('../app/Controllers/'.ucfirst($url[0]).'Controller.php')) {
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
        $this->controller = new $ctrl($db, $this->baseUrl, $cache, $debug);

        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }
        $this->params = $url ? array_values($url) : [];
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    protected function parseUrl($url)
    {
        if (isset($url)) {
            return explode('/', filter_var(strtolower(rtrim($url, '/')), FILTER_SANITIZE_URL));
        }
    }

    protected function setBaseUrl(){
        $this->baseUrl = sprintf(
            "%s://%s",
            isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
            $_SERVER['SERVER_NAME']
        );
    }
}
