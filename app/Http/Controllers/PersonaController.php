<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use OpenApi\Attributes as OA;

#[OA\Info(
    version: "1.0.0",
    title: "API Documentation",
    description: "Mycity test API"
)]
#[OA\Tag(name: "Personas", description: "Gestione delle persone")]
#[OA\SecurityScheme(
    securityScheme: "Bearer",
    type: "apiKey",
    in: "header",
    name: "Authorization",
    description: "JWT Bearer Token"
)]
#[OA\SecurityScheme(
    securityScheme: "Sanctum",
    type: "apiKey",
    in: "header",
    name: "Authorization",
    description: "Sanctum Token"
)]
class PersonaController extends Controller
{

    #[OA\Get(
        path: "/api/personas",
        operationId: "getPersonasList",
        tags: ["Personas"],
        summary: "Get list of personas",
        description: "Returns list of personas",
        security: [ ["Bearer" => []] ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Successful operation",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(ref: "#/components/schemas/Persona")
                )
            )
        ]
    )]
    public function index()
    {
        $personas = Persona::all();
        return response()->json($personas);
    }

    #[OA\Post(
        path: "/api/personas",
        operationId: "createPersona",
        tags: ["Personas"],
        summary: "Create new persona",
        description: "Creates a new persona",
        security: [ ["Bearer" => []] ],
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(ref: "#/components/schemas/Persona")
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Persona created successfully",
                content: new OA\JsonContent(ref: "#/components/schemas/Persona")
            ),
            new OA\Response(
                response: 422,
                description: "Validation error",
                content: new OA\JsonContent(type: "object", properties: [
                    new OA\Property(property: "errors", type: "array", items: new OA\Items(type: "string"))
                ])
            )
        ]
    )]
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nome' => 'required|string|max:255',
            'cognome' => 'required|string|max:255',
            'data_di_nascita' => 'required|date',
            'email' => 'required|email|unique:personas,email',
            'numero' => 'required|string|max:15',
            'codice_fiscale' => 'required|string|size:16|unique:personas,codice_fiscale',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $persona = Persona::create($request->all());

        return response()->json($persona, 201);
    }

    #[OA\Get(
        path: "/api/personas/{id}",
        operationId: "getPersonaById",
        tags: ["Personas"],
        summary: "Get persona by ID",
        description: "Returns a single persona",
        security: [ ["Bearer" => []] ],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Successful operation",
                content: new OA\JsonContent(ref: "#/components/schemas/Persona")
            ),
            new OA\Response(response: 404, description: "Persona not found")
        ]
    )]
    public function show(Persona $persona)
    {
        return response()->json($persona);
    }

    #[OA\Put(
        path: "/api/personas/{id}",
        operationId: "updatePersona",
        tags: ["Personas"],
        summary: "Update existing persona",
        description: "Updates an existing persona",
        security: [ ["Bearer" => []] ],
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(ref: "#/components/schemas/Persona")
        ),
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Persona updated successfully",
                content: new OA\JsonContent(ref: "#/components/schemas/Persona")
            ),
            new OA\Response(
                response: 422,
                description: "Validation error",
                content: new OA\JsonContent(type: "object", properties: [
                    new OA\Property(property: "errors", type: "array", items: new OA\Items(type: "string"))
                ])
            ),
            new OA\Response(response: 404, description: "Persona not found")
        ]
    )]
    public function update(Request $request, Persona $persona)
    {
        $validator = Validator::make($request->all(), [
            'nome' => 'required|string|max:255',
            'cognome' => 'required|string|max:255',
            'data_di_nascita' => 'required|date',
            'email' => 'required|email|unique:personas,email,' . $persona->id,
            'numero' => 'required|string|max:15',
            'codice_fiscale' => 'required|string|size:16|unique:personas,codice_fiscale,' . $persona->id,
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $persona->update($request->all());

        return response()->json($persona);
    }

    #[OA\Delete(
        path: "/api/personas/{id}",
        operationId: "deletePersona",
        tags: ["Personas"],
        summary: "Delete persona",
        description: "Deletes a persona",
        security: [ ["Bearer" => []] ],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(response: 200, description: "Persona deleted successfully"),
            new OA\Response(response: 404, description: "Persona not found")
        ]
    )]
    public function destroy(Persona $persona)
    {
        $persona->delete();

        return response()->json(['message' => 'Persona eliminata con successo.']);
    }
}
