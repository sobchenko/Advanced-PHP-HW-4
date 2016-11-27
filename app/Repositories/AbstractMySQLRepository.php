<?php

namespace Repositories;

abstract class AbstractMySQLRepository
{
    /**
     * @var \PDO DB handler
     */
    protected $db;

    /**
     * @var object Name of model
     */
    protected $model;

    /**
     * @var string
     */
    protected $dbTable;

    public function __construct(\PDO $db, $modelName, $dbTable)
    {
        $this->db = $db;
        $this->model = $modelName;
        $this->dbTable = $dbTable;
    }

    public function __toString()
    {
    }

    public function findById($id)
    {
    }

    /**
     * @return array of a Objects
     */
    public function findAll()
    {
        $result = $this->db->query('SELECT * FROM '.$this->dbTable);
        $result->setFetchMode(\PDO::FETCH_CLASS, $this->model);

        return $result->fetchAll();
    }

    /**
     * @return array of entities ID's
     */
    public function idAll()
    {
        $class_name = end(explode('\\', get_called_class()));
        $result = $this->db->query('SELECT `id` FROM '.$this->dbTable);
        $result->setFetchMode(\PDO::FETCH_COLUMN, 0);

        return $result->fetchAll();
    }
}
