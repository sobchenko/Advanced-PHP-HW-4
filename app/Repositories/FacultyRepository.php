<?php

namespace Repositories;

class FacultyRepository extends AbstractMySQLRepository implements RepositoryInterface
{
    public function getAllFacultiesWithoutDepartment()
    {
        $sqlQuery = "
            SELECT * 
            FROM `{$this->dbTable}` AS `f` 
            WHERE `f`.`id` NOT IN (
              SELECT `fd`.`faculty_id` 
              FROM `faculties_departments` AS `fd` 
              WHERE `fd`.`active` = 1 
            );
        ";
        $result = $this->db->query($sqlQuery);
        $result->setFetchMode(\PDO::FETCH_CLASS, $this->model);

        return $result->fetchAll();
    }

    public function getAllFacultiesWithoutDisciplines()
    {
        $sqlQuery = "
            SELECT * 
            FROM `{$this->dbTable}` AS `f` 
            WHERE `f`.`id` NOT IN (
              SELECT `fd`.`faculty_id` 
              FROM `faculties_disciplines` AS `fd` 
              WHERE `fd`.`active` = 1 
            );
        ";
        $result = $this->db->query($sqlQuery);
        $result->setFetchMode(\PDO::FETCH_CLASS, $this->model);

        return $result->fetchAll();
    }
}
