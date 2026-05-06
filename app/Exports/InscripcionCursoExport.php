<?php

namespace App\Exports;

use App\Models\InscripcionCurso;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class InscripcionCursoExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    public function __construct(private string $search = '', private ?int $feligresId = null) {}

    public function query()
    {
        return InscripcionCurso::with(['feligres.persona', 'curso.tipoCurso'])
            ->when($this->feligresId, fn($q) => $q->where('feligres_id', $this->feligresId))
            ->when($this->search, fn($q) => $q->whereHas('feligres.persona', fn($p) =>
                $p->where('primer_nombre', 'like', "%{$this->search}%")
                  ->orWhere('primer_apellido', 'like', "%{$this->search}%")
            )->orWhereHas('curso', fn($c) =>
                $c->where('nombre', 'like', "%{$this->search}%")
            ))
            ->latest('id');
    }

    public function headings(): array
    {
        return [
            'ID',
            'Feligrés',
            'DNI',
            'Curso',
            'Tipo de Curso',
            'Fecha de Inscripción',
            'Aprobado',
            'Certificado Emitido',
            'Fecha de Certificado',
            'Fecha de Registro',
        ];
    }

    public function map($inscripcion): array
    {
        return [
            $inscripcion->id,
            optional($inscripcion->feligres?->persona)->nombre_completo ?? 'N/A',
            optional($inscripcion->feligres?->persona)->dni ?? 'N/A',
            optional($inscripcion->curso)->nombre ?? 'N/A',
            optional($inscripcion->curso?->tipoCurso)->nombre_curso ?? 'N/A',
            $inscripcion->fecha_inscripcion?->format('d/m/Y') ?? 'N/A',
            $inscripcion->aprobado ? 'Sí' : 'No',
            $inscripcion->certificado_emitido ? 'Sí' : 'No',
            $inscripcion->fecha_certificado?->format('d/m/Y') ?? 'N/A',
            $inscripcion->created_at->format('d/m/Y H:i'),
        ];
    }
}
