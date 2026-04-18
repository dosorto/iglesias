<?php

namespace App\Livewire\Roles;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesEdit extends Component
{
    public $role;
    public $name = '';
    public $selectedPermissions = [];
    private array $instructorRestrictedPrefixes = ['personas.', 'iglesias.', 'religion.'];

    protected function rules()
    {
        return [
            'name' => 'required|min:3|unique:roles,name,' . $this->role->id,
            'selectedPermissions' => 'required|array|min:1',
        ];
    }

    public function messages()
    {
        return [
            'selectedPermissions.required' => 'Debes seleccionar al menos un permiso para el rol.',
            'selectedPermissions.min' => 'El rol debe tener al menos un permiso asignado.',
        ];
    }

    public function mount(Role $role)
    {
        if (in_array($role->name, ['root', 'admin'], true) && ! $this->currentUserIsRoot()) {
            abort(403, 'Solo un usuario root puede editar roles reservados.');
        }

        $this->role = $role;
        $this->name = $role->name;
        $this->selectedPermissions = $role->permissions->pluck('name')->toArray();
    }

    public function update()
    {
        if (in_array($this->role->name, ['root', 'admin'], true) && ! $this->currentUserIsRoot()) {
            session()->flash('error', 'Solo un usuario root puede modificar roles reservados.');
            return;
        }

        $this->validate();

        $this->role->update(['name' => $this->name]);

        $permissionsToSync = $this->sanitizePermissionsForRole($this->name, $this->selectedPermissions);

        if (count($permissionsToSync) !== count($this->selectedPermissions)) {
            session()->flash('warning', 'Para el rol instructor no se permiten permisos de personas, iglesias ni religion. Se removieron automaticamente.');
        }

        $this->role->syncPermissions($permissionsToSync);

        session()->flash('success', 'Rol actualizado correctamente');

        return redirect()->route('roles.index');
    }

    public function render()
    {
        $permissionsGrouped = Permission::all()->groupBy(function ($permission) {
            return explode('.', $permission->name)[0];
        });

        return view('livewire.roles.roles-edit', [
            'permissionsGrouped' => $permissionsGrouped,
        ]);
    }

    private function currentUserIsRoot(): bool
    {
        $authUser = Auth::user();
        $currentUser = $authUser ? User::with('roles')->find($authUser->id) : null;

        return (bool) ($currentUser?->roles?->contains('name', 'root'));
    }

    private function sanitizePermissionsForRole(string $roleName, array $permissions): array
    {
        if (strtolower(trim($roleName)) !== 'instructor') {
            return $permissions;
        }

        return array_values(array_filter($permissions, function ($permissionName) {
            foreach ($this->instructorRestrictedPrefixes as $prefix) {
                if (str_starts_with((string) $permissionName, $prefix)) {
                    return false;
                }
            }

            return true;
        }));
    }
}