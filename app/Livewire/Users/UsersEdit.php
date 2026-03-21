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
    private bool $currentUserIsRoot = false;

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
    }

    public function update()
    {
        $this->validate();

        $selectedHasRoot = in_array('root', $this->selectedRoles, true);

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

        $this->user->syncRoles($this->selectedRoles);

        session()->flash('success', 'Usuario actualizado correctamente');

        return redirect()->route('users.index');
    }

    public function render()
    {
        return view('livewire.users.users-edit', [
            'roles' => Role::all(),
        ]);
    }
}