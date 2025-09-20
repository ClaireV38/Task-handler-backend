<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Services\Modules\Auth\Action\AuthenticateAction;
use App\Services\Modules\Auth\Data\Credentials;
use Illuminate\Http\Request;

final class SessionController
{
    public function __construct(private AuthenticateAction $auth)
    {
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(LoginRequest $request)
    {
        /** @var string $email */
        $email = $request->input('email');

        /** @var string $password */
        $password = $request->input('password');

        $credentials = Credentials::from([
            'email' => $email,
            'password' => $password,
            'ip' => $request->ip(),
        ]);

        if (!$this->auth->login($credentials)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $request->user()?->createToken('api-token')->plainTextToken;

        return response()->json(['token' => $token]);
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $this->auth->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->noContent();
    }
}
