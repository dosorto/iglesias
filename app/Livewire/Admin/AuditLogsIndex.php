<?php

namespace App\Livewire\Admin;

use App\Models\AuditLog;
use App\Exports\AuditLogsExport;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
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
        abort_if(!Gate::allows('audit.export'), 403);
        
        $filename = 'auditoria_' . now()->format('Y_m_d_His') . '.xlsx';
        return Excel::download(new AuditLogsExport($this->search, $this->event, $this->date), $filename);
    }

    public function undoLastDeletion(): void
    {
        abort_if(! $this->canRestoreFromAudit(), 403);

        $lastDeletedLog = AuditLog::query()
            ->where('event', 'deleted')
            ->latest()
            ->first();

        if (! $lastDeletedLog) {
            session()->flash('error', 'No existe una eliminación reciente para deshacer.');
            return;
        }

        $this->restoreFromLog($lastDeletedLog->id);
    }

    public function restoreFromLog(int $logId): void
    {
        abort_if(! $this->canRestoreFromAudit(), 403);

        $log = AuditLog::query()->find($logId);

        if (! $log || $log->event !== 'deleted') {
            session()->flash('error', 'El registro de auditoría seleccionado no es válido para restaurar.');
            return;
        }

        $modelClass = (string) $log->auditable_type;

        if (! class_exists($modelClass) || ! is_subclass_of($modelClass, Model::class)) {
            session()->flash('error', 'No se pudo resolver el tipo de modelo para restaurar.');
            return;
        }

        if (! in_array(SoftDeletes::class, class_uses_recursive($modelClass), true)) {
            session()->flash('error', 'Este registro no usa eliminación lógica, no puede restaurarse desde auditoría.');
            return;
        }

        $record = $modelClass::withTrashed()->find($log->auditable_id);

        if (! $record) {
            session()->flash('error', 'El registro ya no existe en base de datos.');
            return;
        }

        if (! $record->trashed()) {
            session()->flash('warning', 'El registro ya se encuentra activo.');
            return;
        }

        $record->restore();

        session()->flash('success', sprintf(
            'Se restauró correctamente %s #%s.',
            class_basename($modelClass),
            (string) $log->auditable_id
        ));
    }

    public function canRestoreFromAudit(): bool
    {
        $user = Auth::user();

        if (! $user instanceof User) {
            return false;
        }

        return (bool) (
            Gate::forUser($user)->allows('audit.restore')
            || $user->hasAnyRole(['root', 'admin'])
        );
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
