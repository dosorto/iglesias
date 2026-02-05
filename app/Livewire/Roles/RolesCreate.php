<?php

namespace App\Livewire\Roles;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesCreate extends Component
{
    public $name = '';
    public $selectedPermissions = [];

    protected function rules()
    {
        return [
            'name' => 'required|min:3|unique:roles,name',
            'selectedPermissions' => 'array',
        ];
    }

    public function mount()
    {
        // Cargar permisos disponibles
        $this->permissions = Permission::all();
    }

    public function store()
    {
        $this->validate();

        $role = Role::create([
            'name' => $this->name,
        ]);

        $role->syncPermissions($this->selectedPermissions);

        session()->flash('success', 'Rol creado correctamente');

        return redirect()->route('roles.index');
    }

    public function render()
    {
        $permissionsGrouped = Permission::all()->groupBy(function ($permission) {
            return explode('.', $permission->name)[0];
        });

        return view('livewire.roles.roles-create', [
            'permissionsGrouped' => $permissionsGrouped,
        ]);
    }
}