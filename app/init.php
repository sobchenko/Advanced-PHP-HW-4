<?php

require_once 'Core/App.php';
require_once 'Core/Controller.php';
require_once 'Core/DataStorage.php';

$db = new Core\DataStorage('localhost', 'root', 'test', 'test');
var_dump($db);
