<?php

namespace App\Modules\Events\Repositories;

use App\Models\Event;
use App\Modules\Shared\Repositories\BaseRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class EventRepository extends BaseRepository implements EventRepositoryInterface
{
    public function __construct(Event $model)
    {
        parent::__construct($model);
    }

    public function getPublishedEvents(array $filters): LengthAwarePaginator
    {
        return $this->model->with(['venue', 'category', 'ticketTypes'])
            ->where('status', 'published')
            ->when($filters['category'] ?? null, function ($query, $category) {
                $query->whereHas('category', fn ($q) => $q->where('slug', $category));
            })
            ->latest()
            ->paginate(20);
    }
}
