<?php

namespace App\Traits;

use App\Models\User;
use Firebase\JWT\JWT;

trait JwtHelper
{
    protected function generateToken(User $user, $payload = [])
    {
        $payload = array_merge([
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'organization_uuid' => $user->organization_uuid,
            'modules' => [],
            'iss' => env('APP_URL'),
            'exp' => time() + (60 * 60),
        ], $payload);

        return JWT::encode($payload, file_get_contents(config('jwt.private')), config('jwt.algorithm'));
    }

    protected function validateToken($token)
    {
        if (! $token) {
            return null;
        }
        if (strpos($token, 'Bearer') !== false) {
            preg_match('/Bearer\s((.*)\.(.*)\.(.*))/', $token, $jwt);
            $token = $jwt[1];
        }
        try {
            $result = JWT::decode($token, file_get_contents(config('jwt.public')), [config('jwt.algorithm')]);
            $payload = json_decode(json_encode($result), true);
            info('user', ['payload' => $payload]);

            return $payload;
        } catch (\Exception $e) {
            info('error', ['e' => $e]);

            return false;
        }
    }
}
