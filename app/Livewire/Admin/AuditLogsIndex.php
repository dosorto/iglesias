<?php

namespace App\Livewire\Admin;

use App\Models\AuditLog;
use App\Exports\AuditLogsExport;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use Maatwebsite\Excel\Facades\Excel;

class AuditLogsIndex extends Component
{
    use WithPagination;

    #[Url(except: '')]
    public $search = '';

    #[Url(except: '')]
    public $event = '';

    #[Url(except: '')]
    public $date = '';

    public $perPage = 10;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingEvent()
    {
        $this->resetPage();
    }

    public function updatingDate()
    {
        $this->resetPage();
    }

    public function export()
    {
        abort_if(!auth()->user()->can('audit.export'), 403);
        
        $filename = 'auditoria_' . now()->format('Y_m_d_His') . '.xlsx';
        return Excel::download(new AuditLogsExport($this->search, $this->event, $this->date), $filename);
    }

    public function render()
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

        return view('livewire.admin.audit-logs-index', [
            'logs' => $query->paginate($this->perPage)
        ]);
    }
}
