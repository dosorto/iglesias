<?php

namespace App\Exports;

use App\Models\Curso;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CursoExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    public function __construct(private string $search = '') {}

    public function query()
    {
        return Curso::with(['tipoCurso', 'encargado.feligres.persona', 'iglesia'])
            ->when($this->search, fn($q) => $q->where('nombre', 'like', "%{$this->search}%"))
            ->latest('id');
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nombre del Curso',
            'Tipo de Curso',
            'Fecha de Inicio',
            'Fecha de Fin',
            'Estado',
            'Encargado',
            'Parroquia',
            'Fecha de Registro',
        ];
    }

    public function map($curso): array
    {
        return [
            $curso->id,
            $curso->nombre,
            optional($curso->tipoCurso)->nombre_curso ?? 'N/A',
            $curso->fecha_inicio?->format('d/m/Y') ?? 'N/A',
            $curso->fecha_fin?->format('d/m/Y') ?? 'N/A',
            $curso->estado ?? 'N/A',
            optional($curso->encargado?->feligres?->persona)->nombre_completo ?? 'N/A',
            optional($curso->iglesia)->nombre ?? 'N/A',
            $curso->created_at->format('d/m/Y H:i'),
        ];
    }
}
