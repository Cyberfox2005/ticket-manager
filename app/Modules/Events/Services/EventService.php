<?php

namespace App\Modules\Events\Services;

use App\Modules\Events\Repositories\EventRepositoryInterface;

class EventService
{
    public function __construct(protected EventRepositoryInterface $eventRepository) {}

    public function getList(array $filters)
    {
        return $this->eventRepository->getPublishedEvents($filters);
    }

    public function getDetails(int $id)
    {
        $event = $this->eventRepository->find($id);
        if ($event) {
            $event->load(['venue', 'category', 'ticketTypes']);
        }
        return $event;
    }
}
