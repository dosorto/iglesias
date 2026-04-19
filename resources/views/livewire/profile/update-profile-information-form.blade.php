<?php

use App\Models\User;
use App\Models\Encargado;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component
{
    public string $name = '';
    public string $email = '';

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        // Issue #8: Bloquear edición de perfil para rol instructor
        $user = Auth::user();

        if ($user->roles->contains('name', 'instructor')) {
            Session::flash('error', 'No puedes editar tu perfil. Contacta al administrador para realizar cambios.');
            return;
        }

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
        ]);

        $normalizedEmail = Str::lower(trim((string) $validated['email']));
        $currentEmail = Str::lower(trim((string) $user->email));

        if ($normalizedEmail !== $currentEmail) {
            $emailUsadoPorOtroEncargado = Encargado::query()
                ->whereHas('feligres.persona', function ($query) use ($normalizedEmail) {
                    $query->whereRaw('LOWER(email) = ?', [$normalizedEmail]);
                })
                ->exists();

            if ($emailUsadoPorOtroEncargado) {
                $this->addError('email', 'Este correo ya está asignado a otro encargado. Usa uno distinto para evitar problemas al iniciar sesión.');
                return;
            }
        }

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function sendVerification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
}; ?>

<section>
    @php
        $authUser = auth()->user();
        $isInstructor = $authUser?->roles?->contains('name', 'instructor');
        $isEncargado = $authUser?->roles?->contains('name', 'encargado');
    @endphp

    <header>
        <div class="flex flex-wrap items-center gap-2">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
            Información del perfil
            </h2>

            @if($isEncargado)
                <span class="inline-flex items-center rounded-full border border-sky-200 bg-sky-50 px-2.5 py-1 text-xs font-semibold text-sky-700 dark:border-sky-800 dark:bg-sky-900/40 dark:text-sky-200">
                    Encargado
                </span>
            @endif
        </div>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
            Actualiza la información de tu perfil y tu correo electrónico.
        </p>
    </header>

    {{-- Issue #8: Advertencia para instructores --}}
    @if($isInstructor)
        <div class="mt-6 bg-amber-50 border border-amber-200 rounded-lg p-4">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-amber-800 dark:text-amber-200">
                        No puedes editar tu perfil como instructor. Contacta al administrador si necesitas realizar cambios en tu cuenta.
                    </p>
                </div>
            </div>
        </div>
    @else
        <form wire:submit="updateProfileInformation" class="mt-6 space-y-6">
        <div>
            <x-input-label for="name" value="Nombre" />
            <x-text-input wire:model="name" id="name" name="name" type="text" class="mt-1 block w-full" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" value="Correo electrónico" />
            <x-text-input wire:model="email" id="email" name="email" type="email" class="mt-1 block w-full" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! auth()->user()->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                        Tu dirección de correo electrónico no está verificada.

                        <button wire:click.prevent="sendVerification" class="underline text-sm text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Haz clic aquí para reenviar el correo de verificación.
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            Se ha enviado un nuevo enlace de verificación a tu correo electrónico.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4 pt-2">
            <x-primary-button wire:loading.attr="disabled" wire:target="updateProfileInformation">
                <span wire:loading.remove wire:target="updateProfileInformation">Guardar cambios</span>
                <span wire:loading wire:target="updateProfileInformation">Guardando...</span>
            </x-primary-button>

            <x-action-message class="me-3" on="profile-updated">
                Guardado.
            </x-action-message>
        </div>
        </form>
    @endif
</section>
