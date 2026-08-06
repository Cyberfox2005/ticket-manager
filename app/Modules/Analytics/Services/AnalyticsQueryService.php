<?php

namespace App\Modules\Analytics\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsQueryService
{
    /**
     * Revenue stats scoped to the current user's organization.
     */
    public function getRevenueStats(string $period = '30d', ?User $user = null, ?int $eventId = null): array
    {
        $days      = (int) str_replace('d', '', $period);
        $startDate = Carbon::now()->subDays($days);

        $orgId = $user ? optional($user->organizations()->first())->id : null;

        $query = DB::table('daily_stats')
            ->select(DB::raw('date, SUM(revenue) as total_revenue, SUM(tickets_sold) as tickets_sold'))
            ->where('date', '>=', $startDate);

        if ($orgId) {
            // Filter to only events belonging to this organization
            $eventIds = DB::table('events')
                ->where('organization_id', $orgId)
                ->pluck('id');
            $query->whereIn('event_id', $eventIds);
        }

        if ($eventId) {
            $query->where('event_id', $eventId);
        }

        return $query
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->toArray();
    }

    /**
     * Attendance conversion stats for a specific event.
     */
    public function getConversionStats(int $eventId): array
    {
        $totalTickets = DB::table('tickets')
            ->join('orders', 'tickets.order_id', '=', 'orders.id')
            ->where('orders.event_id', $eventId)
            ->count();

        $validatedTickets = DB::table('tickets')
            ->join('orders', 'tickets.order_id', '=', 'orders.id')
            ->where('orders.event_id', $eventId)
            ->whereNotNull('tickets.validated_at')
            ->count();

        return [
            'total'    => $totalTickets,
            'attended' => $validatedTickets,
            'rate'     => $totalTickets > 0 ? round(($validatedTickets / $totalTickets) * 100, 2) : 0,
        ];
    }
}
