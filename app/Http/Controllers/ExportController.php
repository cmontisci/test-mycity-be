<?php

namespace App\Http\Controllers;

use App\Jobs\ExportPersonasToCsv;
use OpenApi\Attributes as OA;

#[OA\Tag(name: "Export", description: "Export in file")]
class ExportController extends Controller
{
    #[OA\Get(
        path: "/api/export/personas",
        operationId: "exportPersonas",
        tags: ["Export"],
        summary: "Esporta le persone in un file CSV e le invia via email",
        description: "Questa api esporta le persone in un file CSV e le invia via emai a info@mycity.it.",
        security: [ ["Bearer" => []] ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Export CSV concluso.",
                content: new OA\JsonContent(
                    type: "object",
                    properties: [
                        new OA\Property(property: "message", type: "string")
                    ]
                )
            )
        ]
    )]
    public function exportPersonas()
    {
        ExportPersonasToCsv::dispatch();

        return response()->json(['message' => 'Export CSV concluso.']);
    }
}
