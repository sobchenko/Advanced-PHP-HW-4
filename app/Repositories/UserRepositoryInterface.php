<?php

namespace Repositories;

use Models\Student;

interface UserRepositoryInterface
{
    public function create(Student $student);

    public function remove(Student $student);

    public function update(Student $student);
}
