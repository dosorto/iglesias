<?php

namespace App\Livewire\Roles;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesCreate extends Component
{
    public $name = '';
    public $selectedPermissions = [];
    private array $instructorRestrictedPrefixes = ['personas.', 'iglesias.', 'religion.'];

    protected function rules()
    {
        return [
            'name' => 'required|min:3|unique:roles,name',
            'selectedPermissions' => 'array',
        ];
    }

    public function store()
    {
        $this->validate();

        $roleName = strtolower(trim((string) $this->name));
        $reservedRoles = ['root', 'admin'];

        if (in_array($roleName, $reservedRoles, true) && ! $this->currentUserIsRoot()) {
            session()->flash('error', 'Solo un usuario root puede crear roles reservados (root/admin).');
            return;
        }

        $role = Role::create([
            'name' => $this->name,
        ]);

        $permissionsToSync = $this->sanitizePermissionsForRole($this->name, $this->selectedPermissions);

        if (count($permissionsToSync) !== count($this->selectedPermissions)) {
            session()->flash('warning', 'Para el rol instructor no se permiten permisos de personas, iglesias ni religion. Se removieron automaticamente.');
        }

        $role->syncPermissions($permissionsToSync);

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