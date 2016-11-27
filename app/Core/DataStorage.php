<?php

namespace Core;

class DataStorage
{
    public $error;

    public $isConnected;

    public $isDbSet;

    public $tableList;

    public $message;

    public $handler;

    protected $dbHost, $dbPort, $dbName, $dbUser, $dbPass;

    /**
     * DataStorage constructor.
     *
     * @param $dbHost
     * @param $dbPort
     * @param $dbName
     * @param $dbUser
     * @param $dbPass
     */
    public function __construct($dbHost, $dbPort, $dbName, $dbUser, $dbPass)
    {
        $this->dbHost = $dbHost;
        $this->dbPort = $dbPort;
        $this->dbName = $dbName;
        $this->dbUser = $dbUser;
        $this->dbPass = $dbPass;
        $this->setConnection();
    }

    protected function setConnection()
    {
        try {
            $this->error = false;
            $this->handler = new \PDO(
                "mysql:host={$this->dbHost};port={$this->dbPort};charset=UTF8",
                $this->dbUser,
                $this->dbPass
            );
            $this->handler->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->isConnected = true;
            $this->setDb();
        } catch (\PDOException $e) {
            $this->error = $e->getMessage();
            $this->isConnected = false;
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
        if ($this->isConnected) {
            try {
                $this->error = false;
                $this->handler->query("CREATE DATABASE IF NOT EXISTS {$this->dbName}");
                $this->setDb();
                $this->message = "Database '{$this->dbName}' was successfully created.";
            } catch (\PDOException $e) {
                $this->error = $e->getMessage();
            }
        }
    }

    public function initDbSchema()
    {
        if ($this->isDbSet) {
            try {
                $this->error = false;
                if (file_exists('../app/init/db_shema.sql')) {
                    $sql = file_get_contents('../app/init/db_shema.sql');
                    $this->handler->query($sql);
                    $this->setTableList();
                } else {
                    $this->message = 'File with DB schema doesn\'t exist.';
                }
            } catch (\PDOException $e) {
                $this->error = $e->getMessage();
            }
        }
    }

    protected function setDb()
    {
        if ($this->isConnected) {
            try {
                $this->error = false;
                $this->handler->query("use {$this->dbName}");
                $this->isDbSet = true;
                $this->setTableList();
            } catch (\PDOException $e) {
                $this->isSetDb = false;
                $this->error = $e->getMessage();
            }
        }
    }

    private function setTableList()
    {
        if ($this->isDbSet) {
            try {
                $this->error = false;
                $query = $this->handler->query('show tables;');
                $this->tableList = $query->fetchAll(\PDO::FETCH_COLUMN);
            } catch (\PDOException $e) {
                $this->error = $e->getMessage();
            }
        }
    }
}
