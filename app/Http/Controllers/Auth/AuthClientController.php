<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Laravel\Passport\HasApiTokens;
use OpenApi\Attributes as OA;

#[OA\Tag(name: "Auth", description: "Auth clients")]
class AuthClientController extends Controller
{
    use HasApiTokens;

    #[OA\Post(
        path: "/api/auth/client/login",
        summary: "Autenticazione client",
        description: "Effettua il login per un client e restituisce un token",
        tags: ["Auth"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["client_id", "secret_id"],
                properties: [
                    new OA\Property(property: "client_id", type: "string", format: "string", example: "client_1"),
                    new OA\Property(property: "secret_id", type: "string", format: "password", example: "secret_1")
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
        $request->validate([
            'client_id' => 'required|string',
            'secret_id' => 'required|string',
        ]);

//        $credentials = request(['client_id', 'secret_id']);

        $client = Client::where('client_id', $request->client_id)->first();

        if (!$client || !$client->validateForPassportPasswordGrant($request->secret_id)) {
            return response()->json(['error' => 'The provided credentials are incorrect.'], 401);
        }

//        $request->session()->regenerate();
//        Auth::guard('client')->setUser($client);
        auth()->guard('client')->setUser($client);

        $tokenResult = $client->createToken('ClientToken', ['client-access']);
//        $token = $tokenResult->token;
//        $token->save();
        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => $tokenResult->token->expires_at,
        ]);
    }

    #[OA\Post(
        path: "/api/auth/client/logout",
        summary: "Logout",
        description: "Effettua il logout del client",
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
        if (!$request->user() ||
            !($request->user()?->tokens[0]?->can('client-access')) ||
            !($request->user() instanceof Client))
        {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->user()->token()->revoke();
        return response()->json(['message' => 'Successfully logged out']);
    }

    #[OA\Get(
        path: "/api/auth/client/profile",
        operationId: "get_profile_client",
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
        if (!$request->user() ||
            !($request->user()?->tokens[0]?->can('client-access')) ||
            !($request->user() instanceof Client))
        {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return response()->json($request->user());
    }
}
