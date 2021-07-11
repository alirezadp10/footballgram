<?php

namespace App\Repositories\Contracts;

interface RepositoryContract
{
    public function find($id);

    public function create($data);

    public function update($id, $data);

    public function delete($id);
}
