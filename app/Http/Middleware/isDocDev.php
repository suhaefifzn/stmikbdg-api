<?php

namespace App\Http\Middleware;

use App\Models\SelfServices\AuthService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;

class isDocDev
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if (Session::exists('token')) {
            $auth = new AuthService();
            $checkToken = $auth->checkToken(Session::get('token'));

            if ($checkToken->getData('data')['status'] == 'success') {
                if (Session::has('role')) {
                    if (isset(Session::get('role')['is_dev'])) {
                        return $next($request);
                    }
                }
            }

            return redirect()->route('logout');
        }

        return redirect()->route('check');
    }
}
