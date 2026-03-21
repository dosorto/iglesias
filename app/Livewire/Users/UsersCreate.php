<?php

namespace App\Livewire\Users;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UsersCreate extends Component
{
    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $selectedRoles = [];
    public bool $canAssignRootRole = false;

    protected function rules()
    {
        return [
            'name' => 'required|min:3|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'selectedRoles' => 'array',
        ];
    }

    public function store()
    {
        $this->validate();

        if (in_array('root', $this->selectedRoles, true) && ! $this->canAssignRootRole) {
            session()->flash('error', 'Solo un usuario root puede asignar el rol root.');
            return;
        }

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        if (!empty($this->selectedRoles)) {
            $user->assignRole($this->selectedRoles);
        }

        session()->flash('success', 'Usuario creado correctamente');

        return redirect()->route('users.index');
    }

    public function render()
    {
        $authUser = Auth::user();
        $currentUser = $authUser ? User::with('roles')->find($authUser->id) : null;
        $this->canAssignRootRole = (bool) ($currentUser?->roles?->contains('name', 'root'));

        return view('livewire.users.users-create', [
            'roles' => Role::all(),
            'canAssignRootRole' => $this->canAssignRootRole,
        ]);
    }
}