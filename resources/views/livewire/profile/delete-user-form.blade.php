<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component
{
    public string $password = '';

    /**
     * Delete the currently authenticated user.
     */
    public function deleteUser(Logout $logout): void
    {
        // Issue #3: Bloquear auto-eliminación de usuario autenticado
        session()->flash('error', 'No puedes eliminar tu propia cuenta desde aquí. Contacta al administrador.');
        return;
    }
}; ?>

<section class="space-y-6">
    <header>
        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
            Gestión de cuenta
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
            Por seguridad, la eliminación de cuentas no está disponible desde esta pantalla.
        </p>
    </header>

    <div class="rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800 dark:border-amber-800 dark:bg-amber-900/30 dark:text-amber-200">
        Si necesitas eliminar tu cuenta, contacta a un administrador del sistema.
    </div>

    <x-secondary-button disabled class="opacity-60 cursor-not-allowed">
        Eliminación deshabilitada
    </x-secondary-button>
</section>
