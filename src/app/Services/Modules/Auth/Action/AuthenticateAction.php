<?php
namespace App\Services\Modules\Auth\Action;

use App\Services\Modules\Auth\Data\Credentials;
use Illuminate\Support\Facades\Auth;
use App\Services\Modules\Auth\Exceptions\AuthRateLimited;

final class AuthenticateAction
{
    public function login(Credentials $credentials): bool
    {
        if (!Auth::attempt([
            'email' => $credentials->email,
            'password' => $credentials->password
        ])) {
            return false;
        }

        return true;
    }

    public function logout(): void
    {
        Auth::logout();
    }
}
