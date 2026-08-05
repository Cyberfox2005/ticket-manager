<?php

namespace App\Http\Middleware;

use App\Modules\Shared\Services\AuditLogService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuditRequestMiddleware
{
    public function __construct(protected AuditLogService $auditLogService) {}

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only log non-GET requests for sensitive operations or specific routes
        if (!$request->isMethod('GET') || $request->is('admin/*')) {
            $this->auditLogService->log(
                action: 'request.' . strtolower($request->method()),
                after: [
                    'url' => $request->fullUrl(),
                    'status' => $response->getStatusCode(),
                ]
            );
        }

        return $response;
    }
}
