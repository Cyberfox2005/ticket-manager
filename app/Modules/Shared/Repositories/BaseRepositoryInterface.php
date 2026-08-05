<?php

namespace App\Modules\Shared\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

interface BaseRepositoryInterface
{
    public function all(): Collection;
    public function find(int $id): ?Model;
    public function create(array $data): Model;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
}
