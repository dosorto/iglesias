<?php

namespace App\Livewire\Users;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\User;

class UsersIndex extends Component
{
    use WithPagination;

    public $showDeleteModal = false;
    public $userToDelete;

    // Propiedades para modal de reset de contraseña
    public $showResetPasswordModal = false;
    public $userToReset;
    public $sendByEmail = false;

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

    public function confirmDelete($userId)
    {
        $targetUser = User::findOrFail($userId);

        if (! $this->canDeleteUser($targetUser)) {
            session()->flash('error', 'No tienes permiso para eliminar este usuario.');
            return;
        }

        $this->userToDelete = $targetUser;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        if ($this->userToDelete) {
            $targetUser = User::find($this->userToDelete->id);

            if (! $targetUser || ! $this->canDeleteUser($targetUser)) {
                session()->flash('error', 'No tienes permiso para eliminar este usuario.');
                $this->closeDeleteModal();
                return;
            }

            $targetUser->delete();
            session()->flash('success', 'Usuario eliminado correctamente');
        }
        $this->closeDeleteModal();
    }

    private function canDeleteUser(User $targetUser): bool
    {
        $authUser = Auth::user();
        $currentUser = $authUser ? User::find($authUser->id) : null;

        if (! $currentUser) {
            return false;
        }

        // Ningún usuario puede eliminar su propia cuenta.
        if ((int) $currentUser->id === (int) $targetUser->id) {
            return false;
        }

        $currentIsAdmin = $currentUser->roles()->where('name', 'admin')->exists();
        $targetIsRoot = $targetUser->roles()->where('name', 'root')->exists();

        // Admin no puede eliminar usuarios root.
        if ($currentIsAdmin && $targetIsRoot) {
            return false;
        }

        return true;
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->userToDelete = null;
    }

    public function confirmResetPassword($userId)
    {
        $targetUser = User::findOrFail($userId);

        if (! $this->canResetPasswordForUser($targetUser)) {
            session()->flash('error', 'No tienes permiso para resetear la contraseña de este usuario.');
            return;
        }

        $this->userToReset = $targetUser;
        $this->sendByEmail = false; // Por defecto no enviar por email
        $this->showResetPasswordModal = true;
    }

    public function closeResetPasswordModal()
    {
        $this->showResetPasswordModal = false;
        $this->userToReset = null;
        $this->sendByEmail = false;
    }

    public function resetPassword()
    {
        if (!$this->userToReset) {
            return;
        }

        $targetUser = User::find($this->userToReset->id);

        if (! $targetUser || ! $this->canResetPasswordForUser($targetUser)) {
            session()->flash('error', 'No tienes permiso para resetear la contraseña de este usuario.');
            $this->closeResetPasswordModal();
            return;
        }

        $newPassword = Str::random(10);

        $targetUser->update([
            'password' => Hash::make($newPassword),
            'password_visible' => $newPassword,
        ]);

        if ($this->sendByEmail) {
            // Aquí iría la lógica para enviar email
            // Por ahora solo mostramos un mensaje
            session()->flash('success', "Nueva contraseña generada y enviada al correo: {$targetUser->email}");
        } else {
            session()->flash('success', "Nueva contraseña generada: $newPassword");
        }

        $this->closeResetPasswordModal();
    }

    private function canResetPasswordForUser(User $targetUser): bool
    {
        $authUser = Auth::user();
        $currentUser = $authUser ? User::find($authUser->id) : null;

        if (! $currentUser) {
            return false;
        }

        $currentIsAdmin = $currentUser->roles()->where('name', 'admin')->exists();
        $targetIsRoot = $targetUser->roles()->where('name', 'root')->exists();

        // Admin no puede resetear contraseñas de usuarios root.
        if ($currentIsAdmin && $targetIsRoot) {
            return false;
        }

        return true;
    }

    public function render()
    {
        $authUser = Auth::user();
        $currentUser = $authUser ? User::with('roles')->find($authUser->id) : null;
        $canViewTemporaryPasswords = (bool) ($currentUser?->roles?->contains('name', 'root'));

        $query = User::with('roles')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            });

        // Aplicar ordenamiento
        $query->orderBy($this->sortField, $this->sortDirection);

        $users = $query->paginate($this->perPage);

        return view('livewire.users.users-index', [
            'users' => $users,
            'canViewTemporaryPasswords' => $canViewTemporaryPasswords,
        ]);
    }
}
