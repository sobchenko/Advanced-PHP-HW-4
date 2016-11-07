<?php

namespace Controllers;

interface ControllerInterface
{
    /**
     * @param params $name
     */
    public function index($name = '');
}
