<?php

namespace App\Livewire\Roles;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class RolesIndex extends Component
{
    use WithPagination;

    public $showDeleteModal = false;
    public $roleToDelete;

    // Propiedades para búsqueda y filtrado
    public $search = '';
    public $perPage = 10;
    public $sortField = 'name';
    public $sortDirection = 'asc';

    protected $queryString = [
        'search' => ['except' => ''],
        'perPage' => ['except' => 10],
        'sortField' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc'],
    ];

    public function mount()
    {
        // No necesitamos cargar datos aquí, Livewire maneja la paginación automáticamente
    }

    public function loadData()
    {
        // Este método ya no es necesario con WithPagination
        // Los datos se cargan automáticamente en el método render()
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->perPage = 10;
        $this->sortField = 'name';
        $this->sortDirection = 'asc';
        $this->resetPage();
    }

    public function confirmDelete($roleId)
    {
        $this->roleToDelete = Role::findOrFail($roleId);
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        if ($this->roleToDelete) {
            $this->roleToDelete->delete();
            session()->flash('success', 'Rol eliminado correctamente');
        }
        $this->closeDeleteModal();
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->roleToDelete = null;
    }

    public function render()
    {
        $query = Role::with('permissions')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            });

        // Aplicar ordenamiento
        $query->orderBy($this->sortField, $this->sortDirection);

        $roles = $query->paginate($this->perPage);

        return view('livewire.roles.roles-index', [
            'roles' => $roles,
        ]);
    }
}
