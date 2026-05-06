<?php

namespace App\Exports;

use App\Models\Matrimonio;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class MatrimonioExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    public function __construct(private string $search = '') {}

    public function query()
    {
        return Matrimonio::with([
            'esposo.persona',
            'esposa.persona',
            'encargado.feligres.persona',
            'iglesia',
        ])
        ->when($this->search, fn($q) => $q->whereHas('esposo.persona', fn($p) =>
            $p->where('primer_nombre', 'like', "%{$this->search}%")
              ->orWhere('primer_apellido', 'like', "%{$this->search}%")
        )->orWhereHas('esposa.persona', fn($p) =>
            $p->where('primer_nombre', 'like', "%{$this->search}%")
              ->orWhere('primer_apellido', 'like', "%{$this->search}%")
        ))
        ->latest('id');
    }

    public function headings(): array
    {
        return [
            'ID',
            'Esposo',
            'Esposa',
            'Fecha de Matrimonio',
            'Libro',
            'Folio',
            'Partida N°',
            'Encargado',
            'Parroquia',
            'Fecha de Registro',
        ];
    }

    public function map($matrimonio): array
    {
        return [
            $matrimonio->id,
            optional($matrimonio->esposo?->persona)->nombre_completo ?? 'N/A',
            optional($matrimonio->esposa?->persona)->nombre_completo ?? 'N/A',
            $matrimonio->fecha_matrimonio?->format('d/m/Y') ?? 'N/A',
            $matrimonio->libro_matrimonio ?? 'N/A',
            $matrimonio->folio ?? 'N/A',
            $matrimonio->partida_numero ?? 'N/A',
            optional($matrimonio->encargado?->feligres?->persona)->nombre_completo ?? 'N/A',
            optional($matrimonio->iglesia)->nombre ?? 'N/A',
            $matrimonio->created_at->format('d/m/Y H:i'),
        ];
    }
}
