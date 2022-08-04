<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\Models\TeamMember;
use App\Models\Role;
use App\Traits\ApiResponser;

class Authenticate
{
    use ApiResponser;

    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct()
    {
        $this->auth = Auth::user();
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (!Auth::check()) {
            return $this->errorResponse('Unauthorized.', 401);
        }

        return $next($request);
    }
}
