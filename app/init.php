<?php

ini_set('display_errors', 1);

require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;

if (!file_exists(__DIR__.'/config/parameters.yml')) {
    die('parameters.yml not found');
}

try {
    $params = Yaml::parse(file_get_contents(__DIR__.'/config/parameters.yml'));
} catch (ParseException $e) {
    printf('Unable to parse the YAML string: %s', $e->getMessage());
}

if ($params['parameters']['environment'] == 'live') {
    ini_set('display_errors', 0);
}

$db = new Core\DataStorage(
    $params['parameters']['db_host'],
    $params['parameters']['db_port'],
    $params['parameters']['db_name'],
    $params['parameters']['db_user'],
    $params['parameters']['db_pass']
);

