<?php

namespace Repositories;

interface UserRepositoryInterface
{
    public function create($user);

    public function remove($user);

    public function update($user);
}
