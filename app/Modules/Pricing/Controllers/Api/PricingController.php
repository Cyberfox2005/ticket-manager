<?php

namespace App\Modules\Pricing\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Modules\Shared\Infrastructure\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PricingController extends Controller
{
    use ApiResponse;

    /**
     * List all active pricing rules.
     */
    public function index(Request $request)
    {
        $rules = DB::table('pricing_rules')
            ->when($request->get('active'), fn ($q) => $q->where('is_active', true))
            ->orderBy('priority', 'desc')
            ->get();

        return $this->successResponse($rules);
    }

    /**
     * Show a single pricing rule.
     */
    public function show(int $id)
    {
        $rule = DB::table('pricing_rules')->find($id);

        if (! $rule) {
            return $this->errorResponse('Pricing rule not found', 404);
        }

        return $this->successResponse($rule);
    }

    /**
     * Create a new pricing rule.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'target_type'      => 'required|in:event,ticket_type,global',
            'target_id'        => 'nullable|integer',
            'rule_type'        => 'required|in:time_based,demand_based,category_based,bulk',
            'conditions'       => 'required|array',
            'adjustment_value' => 'required|numeric',
            'adjustment_type'  => 'required|in:fixed,percentage',
            'priority'         => 'integer|min:0',
            'is_active'        => 'boolean',
        ]);

        $id = DB::table('pricing_rules')->insertGetId(array_merge($validated, [
            'conditions' => json_encode($validated['conditions']),
            'is_active'  => $validated['is_active'] ?? true,
            'priority'   => $validated['priority'] ?? 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]));

        return $this->successResponse(DB::table('pricing_rules')->find($id), 'Pricing rule created', 201);
    }
}
