<?php

namespace Controllers;

interface ControllerInterface
{
    /**
     * @param $param for front controller
     */
    public function index($param = '');
}
