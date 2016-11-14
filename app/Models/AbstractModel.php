<?php

namespace Models;

abstract class AbstractModel implements ModelInterface
{
    /**
//     * @var \PDO DB handler
     */
    protected $db;

    public function __construct(\PDO $db = null)
    {
        if ($db) {
            $this->setHandler($db);
        }
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
        $class_name = end(explode('\\', get_called_class()));
        $result = $this->db->query('SELECT * FROM '.$this->tableNameFromClass($class_name));
        $result->setFetchMode(\PDO::FETCH_CLASS, get_called_class());

        return $result->fetchAll();
    }

    /**
     * @return array of entities ID's
     */
    public function idAll()
    {
        $class_name = end(explode('\\', get_called_class()));
        $result = $this->db->query('SELECT `id` FROM '.$this->tableNameFromClass($class_name));
        $result->setFetchMode(\PDO::FETCH_COLUMN, 0);

        return $result->fetchAll();
    }

    /**
     * Set DB handler for a Class
     *
     * @param \PDO $db
     *
     * @return void
     */
    protected function setHandler(\PDO $db)
    {
        $this->db = $db;
    }

    /**
     * @param $class_name
     *
     * @return string       Prepared table name where all capital letters replaced with dash and small letter
     */
    protected function tableNameFromClass($class_name)
    {
        return strtolower(preg_replace('/\B([A-Z])/', '_$1', $class_name));
//        return strtolower(preg_replace('/(?<=[a-z])([A-Z]+)/', '_$1', $class_name));
    }
}
