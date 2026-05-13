<?php

namespace App\Exports;

use App\Models\Feligres;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class FeligresExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    public function __construct(private string $search = '') {}

    public function query()
    {
        return Feligres::with(['persona', 'iglesia'])
            ->when($this->search, fn($q) => $q->whereHas('persona', fn($p) =>
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
            'DNI',
            'Nombre Completo',
            'Teléfono',
            'Email',
            'Fecha de Nacimiento',
            'Parroquia',
            'Estado',
            'Fecha de Ingreso',
            'Fecha de Registro',
        ];
    }

    public function map($feligres): array
    {
        return [
            $feligres->id,
            $feligres->persona->dni ?? 'N/A',
            $feligres->persona->nombre_completo ?? 'N/A',
            $feligres->persona->telefono ?? 'N/A',
            $feligres->persona->email ?? 'N/A',
            $feligres->persona->fecha_nacimiento?->format('d/m/Y') ?? 'N/A',
            $feligres->iglesia->nombre ?? 'N/A',
            $feligres->estado,
            $feligres->fecha_ingreso?->format('d/m/Y') ?? 'N/A',
            $feligres->created_at->format('d/m/Y H:i'),
        ];
    }
}
