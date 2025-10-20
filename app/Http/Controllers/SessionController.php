<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OpenApi\Attributes as OA;
use App\Http\Requests\LoginRequest;
use App\Services\Modules\Auth\Data\Credentials;
use App\Services\Modules\Auth\Action\AuthenticateAction;

final class SessionController
{
    public function __construct(private AuthenticateAction $auth)
    {
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    #[OA\Post(
        path: '/api/login',
        operationId: 'loginUser',
        description: 'Logs in a user with email and password, returning a Sanctum API token.',
        summary: 'Authenticate user and return a token',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    type: 'object',
                    required: ['email', 'password'],
                    properties: [
                        new OA\Property(
                            property: 'email',
                            type: 'string',
                            format: 'email',
                            example: 'user@example.com'
                        ),
                        new OA\Property(property: 'password', type: 'string', example: 'password')
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful login',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'token', type: 'string', example: '1|abc123def456...')
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Invalid credentials'
            )
        ]
    )]
    public function store(LoginRequest $request)
    {
        /** @var string $email */
        $email = $request->input('email');

        /** @var string $password */
        $password = $request->input('password');

        $credentials = Credentials::from([
            'email'    => $email,
            'password' => $password,
            'ip'       => $request->ip(),
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
