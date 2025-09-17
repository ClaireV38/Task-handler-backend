<?php
namespace App\Http\Controllers;

use App\Services\Modules\Auth\Action\AuthenticateAction;
use App\Services\Modules\Auth\Data\Credentials;
use Illuminate\Http\Request;

final class SessionController
{
    public function __construct(private AuthenticateAction $auth) {}

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $credentials = Credentials::from([
            'email' => $request->email,
            'password' => $request->password,
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
