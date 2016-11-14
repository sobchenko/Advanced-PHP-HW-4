<?php

namespace Controllers;

interface ControllerInterface
{
    /**
     * @param string $param for front controller
     */
    public function index($param = '');
}
