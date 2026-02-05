<?php

namespace App\Exports;

use App\Models\AuditLog;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AuditLogsExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $search;
    protected $event;
    protected $date;

    public function __construct($search = null, $event = null, $date = null)
    {
        $this->search = $search;
        $this->event = $event;
        $this->date = $date;
    }

    public function query()
    {
        $query = AuditLog::with('user')->latest();

        if ($this->search) {
            $query->where(function($q) {
                $q->where('auditable_type', 'like', '%' . $this->search . '%')
                  ->orWhere('auditable_id', 'like', '%' . $this->search . '%')
                  ->orWhereHas('user', function($qu) {
                      $qu->where('name', 'like', '%' . $this->search . '%');
                  });
            });
        }

        if ($this->event) {
            $query->where('event', $this->event);
        }

        if ($this->date) {
            $query->whereDate('created_at', $this->date);
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Fecha',
            'Usuario',
            'Evento',
            'Modelo',
            'Registro ID',
            'Valores Anteriores',
            'Valores Nuevos',
            'IP',
            'URL',
            'User Agent'
        ];
    }

    public function map($log): array
    {
        return [
            $log->id,
            $log->created_at->format('d/m/Y H:i:s'),
            $log->user_name ?? ($log->user->name ?? 'Sistema'),
            strtoupper($log->event),
            class_basename($log->auditable_type),
            $log->auditable_id,
            json_encode($log->old_values),
            json_encode($log->new_values),
            $log->ip_address,
            $log->url,
            $log->user_agent,
        ];
    }
}
