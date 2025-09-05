<?php
namespace App\Domain\Auth\Actions;

use App\Domain\Auth\Data\Credentials;
use Illuminate\Support\Facades\Auth;
use App\Domain\Auth\Exceptions\AuthRateLimited;

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
