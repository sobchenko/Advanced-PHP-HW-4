<?php

namespace Core;

class DataStorage
{
    public $error;

    public $connected;

    public $message;

    private $handler;

    protected $dbHost, $dbPort, $dbName, $dbUser, $dbPass;

    public function __construct($dbHost, $dbPort, $dbName, $dbUser, $dbPass)
    {
        $this->dbHost = $dbHost;
        $this->dbPort = $dbPort;
        $this->dbName = $dbName;
        $this->dbUser = $dbUser;
        $this->dbPass = $dbPass;
        $this->setConnection();
    }

    protected function setConnection() {
        try {
            $this->error = false;
            $this->handler = new \PDO(
                "mysql:host={$this->dbHost};port={$this->dbPort};charset=UTF8",
                $this->dbUser,
                $this->dbPass
            );
            $this->handler->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->connected = true;
            $this->setDb();
        } catch (\PDOException $e) {
            $this->error = $e->getMessage();
            $this->connected = false;
        }
    }

    public function getHandler()
    {
        return $this->handler;
    }

    public function getDbName()
    {
        return $this->dbName;
    }

    public function getDbHost()
    {
        return $this->dbHost;
    }

    public function createDb()
    {
        try {
            $this->error = false;
            $this->handler->query("CREATE DATABASE IF NOT EXISTS {$this->dbName}");
            $this->setDb();
            $this->message = "Database '{$this->dbName}' was successfully created.";
        } catch (\PDOException $e) {
            $this->error = $e->getMessage();
        }
    }

    protected function setDb()
    {
        try {
            $this->error = false;
            $this->handler->query("use {$this->dbName}");
        } catch (\PDOException $e) {
            $this->error = $e->getMessage();
        }
    }
}
