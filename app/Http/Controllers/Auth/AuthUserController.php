<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Passport\HasApiTokens;
use OpenApi\Attributes as OA;
use Illuminate\Support\Facades\Auth;

#[OA\Tag(name: "Auth", description: "Auth users")]
class AuthUserController extends Controller
{
    use HasApiTokens;

    #[OA\Post(
        path: "/api/auth/user/login",
        summary: "Autenticazione utente",
        description: "Effettua il login per un utente e restituisce un token",
        tags: ["Auth"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["email", "password"],
                properties: [
                    new OA\Property(property: "email", type: "string", format: "email", example: "admin@admin.com"),
                    new OA\Property(property: "password", type: "string", format: "password", example: "admin")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Auth effettuato con successo",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "access_token", type: "string", example: "1|abcdefghijklmnopqrstuvwxyz123456"),
                        new OA\Property(property: "token_type", type: "string", example: "Bearer"),
                        new OA\Property(property: "expires_at", type: "date", example: "2025-08-02T22:06:31.000000Z")
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: "Credenziali non valide",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "Unauthorized")
                    ]
                )
            )
        ]
    )]
    public function login(Request $request)
    {
        // Logica di autenticazione per gli utenti
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = request(['email', 'password']);

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;

        $token->save();

        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => $tokenResult->token->expires_at,
        ]);
    }

    #[OA\Post(
        path: "/api/auth/user/logout",
        summary: "Logout",
        description: "Effettua il logout dello user",
        security: [ ["Bearer" => []] ],
        tags: ["Auth"],
        requestBody: new OA\RequestBody(),
        responses: [
            new OA\Response(
                response: 200,
                description: "Logout effettuato con successo",
                content: new OA\JsonContent()
            ),
        ]
    )]
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json(['message' => 'Successfully logged out']);
    }

    #[OA\Get(
        path: "/api/auth/user/profile",
        operationId: "get_user_client",
        tags: ["Auth"],
        summary: "Get user profile",
        description: "Get profile",
        security: [ ["Bearer" => []] ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Successful operation",
                content: new OA\JsonContent()
            )
        ]
    )]
    public function getProfile(Request $request)
    {
        return response()->json($request->user());
    }
}
