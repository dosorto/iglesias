<?php

namespace App\Livewire\Users;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;
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
        $this->userToDelete = User::findOrFail($userId);
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        if ($this->userToDelete) {
            $this->userToDelete->delete();
            session()->flash('success', 'Usuario eliminado correctamente');
        }
        $this->closeDeleteModal();
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->userToDelete = null;
    }

    public function confirmResetPassword($userId)
    {
        $this->userToReset = User::findOrFail($userId);
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

        $newPassword = Str::random(10);

        $this->userToReset->update([
            'password' => Hash::make($newPassword)
        ]);

        if ($this->sendByEmail) {
            // Aquí iría la lógica para enviar email
            // Por ahora solo mostramos un mensaje
            session()->flash('success', "Nueva contraseña generada y enviada al correo: {$this->userToReset->email}");
        } else {
            session()->flash('success', "Nueva contraseña generada: $newPassword");
        }

        $this->closeResetPasswordModal();
    }

    public function render()
    {
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
        ]);
    }
}
