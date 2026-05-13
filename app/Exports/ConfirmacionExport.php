<?php

namespace App\Exports;

use App\Models\Confirmacion;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ConfirmacionExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    public function __construct(private string $search = '') {}

    public function query()
    {
        return Confirmacion::with([
            'feligres.persona',
            'padrino.persona',
            'madrina.persona',
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
            'Confirmado',
            'DNI',
            'Fecha de Confirmación',
            'Lugar',
            'Padrino',
            'Madrina',
            'Libro',
            'Folio',
            'Partida N°',
            'Encargado',
            'Parroquia',
            'Fecha de Registro',
        ];
    }

    public function map($confirmacion): array
    {
        return [
            $confirmacion->id,
            optional($confirmacion->feligres?->persona)->nombre_completo ?? 'N/A',
            optional($confirmacion->feligres?->persona)->dni ?? 'N/A',
            $confirmacion->fecha_confirmacion?->format('d/m/Y') ?? 'N/A',
            $confirmacion->lugar_confirmacion ?? 'N/A',
            optional($confirmacion->padrino?->persona)->nombre_completo ?? 'N/A',
            optional($confirmacion->madrina?->persona)->nombre_completo ?? 'N/A',
            $confirmacion->libro_confirmacion ?? 'N/A',
            $confirmacion->folio ?? 'N/A',
            $confirmacion->partida_numero ?? 'N/A',
            optional($confirmacion->encargado?->feligres?->persona)->nombre_completo ?? 'N/A',
            optional($confirmacion->iglesia)->nombre ?? 'N/A',
            $confirmacion->created_at->format('d/m/Y H:i'),
        ];
    }
}
