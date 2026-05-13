<?php

namespace App\Livewire\Feligres;

use App\Exports\FeligresExport;
use App\Models\Feligres;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use Maatwebsite\Excel\Facades\Excel;

class FeligresIndex extends Component
{
    use WithPagination;

    #[Url(except: '')]
    public $search = '';

    public $perPage = 10;

    public $showDeleteModal = false;
    public $feligresIdBeingDeleted = null;
    public $feligresNameBeingDeleted = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function confirmFeligresDeletion($id, $name)
    {
        $this->feligresIdBeingDeleted = $id;
        $this->feligresNameBeingDeleted = $name;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        $feligres = Feligres::findOrFail($this->feligresIdBeingDeleted);
        $feligres->delete();

        $this->showDeleteModal = false;

        session()->flash('success', 'Feligrés eliminado exitosamente.');
    }

    public function export()
    {
        abort_if(!auth()->user()->can('feligres.view'), 403);
        return Excel::download(new FeligresExport($this->search), 'feligreses_' . now()->format('Y_m_d_His') . '.xlsx');
    }

    public function render()
    {
        $feligres = Feligres::with(['persona', 'iglesia'])
            ->where(function ($query) {
                $query->whereHas('persona', function ($q) {
                    $q->where('primer_nombre', 'like', '%' . $this->search . '%')
                        ->orWhere('segundo_nombre', 'like', '%' . $this->search . '%')
                        ->orWhere('primer_apellido', 'like', '%' . $this->search . '%')
                        ->orWhere('segundo_apellido', 'like', '%' . $this->search . '%')
                        ->orWhere('dni', 'like', '%' . $this->search . '%');
                })->orWhereHas('iglesia', function ($q) {
                    $q->where('nombre', 'like', '%' . $this->search . '%');
                });
            })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.feligres.feligres-index', [
            'feligres' => $feligres,
        ]);
    }
}
