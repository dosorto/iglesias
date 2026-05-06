<?php

namespace App\Exports;

use App\Models\PrimeraComunion;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PrimeraComunionExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    public function __construct(private string $search = '') {}

    public function query()
    {
        return PrimeraComunion::with([
            'feligres.persona',
            'encargado.feligres.persona',
            'iglesia',
        ])
        ->when($this->search, fn($q) => $q->whereHas('feligres.persona', fn($p) =>
            $p->where('primer_nombre', 'like', "%{$this->search}%")
              ->orWhere('primer_apellido', 'like', "%{$this->search}%")
              ->orWhere('dni', 'like', "%{$this->search}%")
        ))
        ->latest('id');
    }

    public function headings(): array
    {
        return [
            'ID',
            'Feligrés',
            'DNI',
            'Fecha de Primera Comunión',
            'Lugar',
            'Libro',
            'Folio',
            'Partida N°',
            'Encargado',
            'Parroquia',
            'Fecha de Registro',
        ];
    }

    public function map($comunion): array
    {
        return [
            $comunion->id,
            optional($comunion->feligres?->persona)->nombre_completo ?? 'N/A',
            optional($comunion->feligres?->persona)->dni ?? 'N/A',
            $comunion->fecha_primera_comunion?->format('d/m/Y') ?? 'N/A',
            $comunion->lugar_celebracion ?? 'N/A',
            $comunion->libro_comunion ?? 'N/A',
            $comunion->folio ?? 'N/A',
            $comunion->partida_numero ?? 'N/A',
            optional($comunion->encargado?->feligres?->persona)->nombre_completo ?? 'N/A',
            optional($comunion->iglesia)->nombre ?? 'N/A',
            $comunion->created_at->format('d/m/Y H:i'),
        ];
    }
}
