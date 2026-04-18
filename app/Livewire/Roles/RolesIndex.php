<?php

namespace App\Livewire\Roles;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
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
        $role = Role::findOrFail($roleId);

        if (in_array($role->name, ['root', 'admin'], true) && ! $this->currentUserIsRoot()) {
            session()->flash('error', 'Solo un usuario root puede eliminar roles reservados.');
            return;
        }

        $this->roleToDelete = $role;
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

    private function currentUserIsRoot(): bool
    {
        $authUser = Auth::user();
        $currentUser = $authUser ? User::with('roles')->find($authUser->id) : null;

        return (bool) ($currentUser?->roles?->contains('name', 'root'));
    }
}
