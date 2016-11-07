<?php

namespace Core;

class DataStorage
{
    private $handler;

    public function __construct($db_host, $db_name, $db_user, $db_pass)
    {
        try {
            $this->handler = new \PDO("mysql:host={$db_host};dbname={$db_name};charset=UTF8", $db_user, $db_pass);
            $this->handler->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function getPDO()
    {
        return $this->handler;
    }
}