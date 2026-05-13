<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentoGenerado extends Model
{
    use HasFactory;

    protected $table = 'documentos_generados';

    protected $fillable = [
        'tipo_documento',
        'fuente_tipo',
        'fuente_id',
        'iglesia_id',
        'fecha_emision',
        'nombre_archivo',
        'path_pdf',
        'payload',
        'codigo_verificacion',
        'hash_payload',
        'created_by',
    ];

    protected $casts = [
        'fecha_emision' => 'datetime',
        'payload' => 'array',
    ];
}
