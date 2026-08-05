<?php

namespace App\Modules\Events\Repositories;

use App\Modules\Shared\Repositories\BaseRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

interface EventRepositoryInterface extends BaseRepositoryInterface
{
    public function getPublishedEvents(array $filters): LengthAwarePaginator;
}
