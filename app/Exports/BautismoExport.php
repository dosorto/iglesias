<?php

namespace App\Exports;

use App\Models\Bautismo;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BautismoExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    public function __construct(private string $search = '') {}

    public function query()
    {
        return Bautismo::with([
            'bautizado.persona',
            'padre.persona',
            'madre.persona',
            'encargado.feligres.persona',
            'iglesia',
        ])
        ->when($this->search, fn($q) => $q->whereHas('bautizado.persona', fn($p) =>
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
            'Bautizado',
            'DNI Bautizado',
            'Fecha de Bautismo',
            'Padre',
            'Madre',
            'Libro',
            'Folio',
            'Partida N°',
            'Párroco Celebrante',
            'Lugar de Nacimiento',
            'Encargado',
            'Parroquia',
            'Fecha de Registro',
        ];
    }

    public function map($bautismo): array
    {
        return [
            $bautismo->id,
            optional($bautismo->bautizado?->persona)->nombre_completo ?? 'N/A',
            optional($bautismo->bautizado?->persona)->dni ?? 'N/A',
            $bautismo->fecha_bautismo?->format('d/m/Y') ?? 'N/A',
            optional($bautismo->padre?->persona)->nombre_completo ?? 'N/A',
            optional($bautismo->madre?->persona)->nombre_completo ?? 'N/A',
            $bautismo->libro_bautismo ?? 'N/A',
            $bautismo->folio ?? 'N/A',
            $bautismo->partida_numero ?? 'N/A',
            $bautismo->parroco_celebrante ?? 'N/A',
            $bautismo->lugar_nacimiento ?? 'N/A',
            optional($bautismo->encargado?->feligres?->persona)->nombre_completo ?? 'N/A',
            optional($bautismo->iglesia)->nombre ?? 'N/A',
            $bautismo->created_at->format('d/m/Y H:i'),
        ];
    }
}
