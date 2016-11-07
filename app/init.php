<?php

require_once  __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;

try {
    $params = Yaml::parse(file_get_contents(__DIR__ . '/config/parameters.yml'));
} catch (ParseException $e) {
    printf("Unable to parse the YAML string: %s", $e->getMessage());
}


$db = new Core\DataStorage(
    $params['parameters']['db_host'],
    $params['parameters']['db_port'],
    $params['parameters']['db_name'],
    $params['parameters']['db_user'],
    $params['parameters']['db_pass']
);

