<?php

namespace App\Exports;

use App\Models\TipoCurso;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TipoCursoExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    public function query()
    {
        return TipoCurso::query()->latest('id');
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nombre del Curso',
            'Descripción',
            'Estado',
            'Fecha de Registro',
        ];
    }

    public function map($tipoCurso): array
    {
        return [
            $tipoCurso->id,
            $tipoCurso->nombre_curso,
            $tipoCurso->descripcion_curso ?? '',
            ucfirst($tipoCurso->estado_curso),
            $tipoCurso->created_at->format('d/m/Y H:i'),
        ];
    }
}
