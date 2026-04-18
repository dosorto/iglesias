<?php

namespace App\Livewire\Users;

use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UsersEdit extends Component
{
    public $user;
    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $selectedRoles = [];
    public bool $canAssignRootRole = true;

    private bool $isEditingSelf = false;
    private bool $currentUserIsAdmin = false;
    private bool $currentUserIsRoot = false;
    private array $originalSelectedRoles = [];

    protected function rules()
    {
        return [
            'name' => 'required|min:3|max:255',
            'email' => 'required|email|unique:users,email,' . $this->user->id,
            'password' => 'nullable|min:8|confirmed',
            'selectedRoles' => 'array',
        ];
    }

    public function mount(User $user)
    {
        $authUser = Auth::user();
        $currentUser = $authUser ? User::find($authUser->id) : null;
        $currentIsAdmin = $currentUser ? $currentUser->roles()->where('name', 'admin')->exists() : false;
        $this->currentUserIsAdmin = $currentIsAdmin;
        $this->currentUserIsRoot = $currentUser ? $currentUser->roles()->where('name', 'root')->exists() : false;
        $targetIsRoot = $user->roles()->where('name', 'root')->exists();
        $this->isEditingSelf = $currentUser ? ((int) $currentUser->id === (int) $user->id) : false;

        // Solo usuarios que ya son root pueden asignar el rol root.
        $this->canAssignRootRole = $this->currentUserIsRoot;

        if ($currentIsAdmin && $targetIsRoot) {
            abort(403, 'No tienes permiso para editar usuarios root.');
        }

        $this->user = $user;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->selectedRoles = $user->roles->pluck('name')->toArray();
        $this->originalSelectedRoles = $this->selectedRoles;
    }

    public function update()
    {
        $this->validate();

        // Issue #1: Impedir edición de permisos del usuario root
        if ($this->user->roles()->where('name', 'root')->exists() && !$this->currentUserIsRoot) {
            session()->flash('error', 'No tienes permiso para editar usuarios root.');
            return;
        }

        // Issue #2: Restringir edición de permisos según jerarquía de roles
        $authUser = Auth::user();
        $authRoles = $authUser->roles->pluck('name')->toArray();
        $targetRoles = $this->user->roles->pluck('name')->toArray();

        $currentUserHierarchy = $this->getHighestRoleHierarchy($authRoles);
        $targetUserHierarchy = $this->getHighestRoleHierarchy($targetRoles);

        if ($targetUserHierarchy >= $currentUserHierarchy) {
            session()->flash('error', 'No puedes editar permisos de usuarios con tu mismo nivel o superior.');
            return;
        }

        $selectedHasRoot = in_array('root', $this->selectedRoles, true);

        if (
            $this->currentUserIsAdmin
            && ! $this->currentUserIsRoot
            && $this->isEditingSelf
            && $this->rolesChangedByAdmin()
        ) {
            session()->flash('error', 'Un usuario admin no puede cambiar sus propios roles.');
            return;
        }

        if ($selectedHasRoot && ! $this->currentUserIsRoot) {
            session()->flash('error', 'Solo un usuario root puede asignar el rol root.');
            return;
        }

        $this->user->update([
            'name' => $this->name,
            'email' => $this->email,
        ]);

        if ($this->password) {
            $this->user->update([
                'password' => Hash::make($this->password),
            ]);
        }

        // Issue #16: Generar credenciales temporales cuando se asigna rol instructor por primera vez
        $isAssigningInstructor = in_array('instructor', $this->selectedRoles, true)
            && !in_array('instructor', $this->originalSelectedRoles, true);

        $credentials = null;
        if ($isAssigningInstructor) {
            $credentials = $this->generateInstructorCredentials($this->user);
        }

        $this->user->syncRoles($this->selectedRoles);

        session()->flash('success', 'Usuario actualizado correctamente');
        if ($credentials) {
            session()->flash('user_credentials', $credentials);
        }

        return redirect()->route('users.index');
    }

    public function render()
    {
        return view('livewire.users.users-edit', [
            'roles' => Role::all(),
        ]);
    }

    private function rolesChangedByAdmin(): bool
    {
        $current = $this->selectedRoles;
        $original = $this->originalSelectedRoles;

        sort($current);
        sort($original);

        return $current !== $original;
    }

    private function getHighestRoleHierarchy(array $roles): int
    {
        $hierarchy = ['root' => 3, 'admin' => 2, 'instructor' => 1];
        $highest = 0;
        foreach ($roles as $role) {
            if (isset($hierarchy[$role])) {
                $highest = max($highest, $hierarchy[$role]);
            }
        }
        return $highest;
    }

    private function generateInstructorCredentials(User $user): array
    {
        $generatedPassword = $this->generarContrasenaTemporal($user->name);

        return [
            'email' => $user->email,
            'password' => $generatedPassword,
        ];
    }

    private function generarContrasenaTemporal(string $nombre): string
    {
        $base = strtolower(trim((string) $nombre));
        $base = preg_replace('/[^a-z0-9]/', '', $base) ?: 'usuario';

        return $base . random_int(1000, 9999);
    }
}