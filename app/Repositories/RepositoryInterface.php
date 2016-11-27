<?php

namespace Repositories;

interface RepositoryInterface
{
    /*
     * @param int @id
     *
     * @return object with such id if it exists, otherwise void
     */
    public function findById($id);

    /*
     * @return array of objects
     */
    public function findAll();
}
