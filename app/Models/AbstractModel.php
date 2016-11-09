<?php

namespace Models;

abstract class AbstractModel implements ModelInterface
{
    protected $handler;

    public function __construct($handler = '')
    {
        if ($handler) {
            $this->setHandler($handler);
        }
    }

    public function __toString()
    {
    }

    public function findById($id)
    {
    }

    public function findAll()
    {
        $class_name = end(explode('\\', get_called_class()));
        $result = $this->handler->query('SELECT * FROM '.strtolower($class_name));
        $result->setFetchMode(\PDO::FETCH_CLASS, get_called_class());
        $collection = [];
        while ($r = $result->fetch()) {
            $r->setHandler($this->handler);
            $collection[] = $r;
        }

        return $collection;
    }

    public function getForTwig()
    {
        $class_name = end(explode('\\', get_called_class()));
        $result = $this->handler->query('SELECT * FROM '.strtolower($class_name));
        $result->setFetchMode(\PDO::FETCH_CLASS, get_called_class());
        $vars = get_class_vars(get_called_class());
        while ($model = $result->fetch()) {
            $r = '';
            foreach ($vars as $k => $v) {
                $r .= $model->$k.' ';
            }
            $collection[] = $r;
        }

        return $collection;
    }

    protected function setHandler($handler)
    {
        $this->handler = $handler;
    }
}
