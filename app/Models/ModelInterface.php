<?php

namespace Models;

interface ModelInterface
{
    /*
     * @param int @id
     *
     * @return object with such id if it is exist
     */
    public function findById($id);

    /*
     * @return all objects
     */
    public function findAll();
}
