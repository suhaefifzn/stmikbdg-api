<?php

namespace App\Http\Middleware;

use App\Models\Users\AllStaffView;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class isSecretary
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $staff = AllStaffView::where('user_id', auth()->user()->id)
            ->select('staff_id', 'user_id', 'is_secretary')
            ->first();

        if (auth()->check() and $staff['is_secretary']) {
            return $next($request);
        } else {
            return response()->json([
                'status' => 'fail',
                'message' => 'Forbidden access'
            ], 403);
        }
    }
}
