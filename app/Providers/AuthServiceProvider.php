<?php

namespace App\Providers;

use App\Models\User;
use App\Traits\JwtHelper;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    use JwtHelper;

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        $this->app['auth']->viaRequest('api', function ($request) {
            $payload = $this->validateToken($request->header('Authorization'));
            return $payload ? new User([], collect($payload)->only(['id', 'first_name', 'last_name', 'email', 'organization_id'])->toArray()) : null;
        });
    }
}
