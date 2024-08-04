<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "Persona",
    type: "object",
    title: "Persona",
    required: ["nome", "cognome", "data_di_nascita", "email", "telefono", "codice_fiscale"],
    properties: [
        new OA\Property(property: "id", type: "integer", format: "int64"),
        new OA\Property(property: "nome", type: "string"),
        new OA\Property(property: "cognome", type: "string"),
        new OA\Property(property: "data_di_nascita", type: "string", format: "date"),
        new OA\Property(property: "email", type: "string", format: "email"),
        new OA\Property(property: "telefono", type: "string"),
        new OA\Property(property: "codice_fiscale", type: "string")
    ]
)]
class Persona extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'cognome',
        'data_di_nascita',
        'email',
        'telefono',
        'codice_fiscale'
    ];
}
