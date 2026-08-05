<?php

namespace App\Modules\Tickets\Repositories;

use App\Modules\Shared\Repositories\BaseRepositoryInterface;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface TicketRepositoryInterface extends BaseRepositoryInterface
{
    public function getTicketsForUser(User $user): Collection;
    public function findByQrHash(string $hash);
}
