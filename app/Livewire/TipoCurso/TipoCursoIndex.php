<?php

namespace App\Livewire\Tipocurso;

use App\Models\TipoCurso as TipoCursoModel;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

class TipocursoIndex extends Component
{
    use WithPagination;

    #[Url(except: '')]
    public string $search = '';

    public int $perPage = 10;

    // Modal eliminar
    public bool $showDeleteModal = false;
    public ?int $tipoCursoIdBeingDeleted = null;
    public string $tipoCursoNameBeingDeleted = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function confirmTipoCursoDeletion(int $id, string $name): void
    {
        $this->tipoCursoIdBeingDeleted = $id;
        $this->tipoCursoNameBeingDeleted = $name;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        if (!$this->tipoCursoIdBeingDeleted) {
            $this->showDeleteModal = false;
            return;
        }

        $tipoCurso = TipoCursoModel::findOrFail($this->tipoCursoIdBeingDeleted);
        $tipoCurso->delete();

        $this->reset(['showDeleteModal', 'tipoCursoIdBeingDeleted', 'tipoCursoNameBeingDeleted']);

        session()->flash('success', 'Tipo de Curso eliminado exitosamente.');
        $this->resetPage();
    }

    public function render()
    {
        $tipocursos = TipoCursoModel::query()
            // AJUSTA el campo según tu tabla:
            // Si tu tabla tiene 'nombre_curso' (por tu seeder), deja esta línea:
            ->when($this->search !== '', fn ($q) => $q->where('nombre_curso', 'like', "%{$this->search}%"))
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.tipocurso.tipocurso-index', [
            'tipocursos' => $tipocursos,
        ]);
    }
}
