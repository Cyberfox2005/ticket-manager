<?php

namespace App\Modules\Analytics\Services;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsQueryService
{
    /**
     * Optimized SQL for Dashboard Revenue
     */
    public function getRevenueStats(string $period = '30d'): array
    {
        $days = (int) str_replace('d', '', $period);
        $startDate = Carbon::now()->subDays($days);

        return DB::table('daily_stats')
            ->select(DB::raw('date, SUM(revenue) as total_revenue'))
            ->where('date', '>=', $startDate)
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->toArray();
    }

    /**
     * Attendance Conversion Logic
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
            'total' => $totalTickets,
            'attended' => $validatedTickets,
            'rate' => $totalTickets > 0 ? round(($validatedTickets / $totalTickets) * 100, 2) : 0
        ];
    }
}
