<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="login">
        <div class="mb-6 border-b border-[#e4e2ef] pb-4">
            <h1 class="font-serif text-4xl text-[#1a1f46] mb-2">Bienvenido de nuevo</h1>
        </div>

        <!-- Correo electronico -->
        <div>
            <x-input-label for="email" value="Correo electronico" class="uppercase tracking-[0.08em] text-xs text-[#1d2247]" />
            <x-text-input wire:model="form.email" id="email" class="block mt-2 w-full rounded-xl border-[#d7d6e7] bg-[#ecebf5] focus:border-[#4f5cf0] focus:ring-[#4f5cf0]" type="email" name="email" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
        </div>

        <!-- Contrasena -->
        <div class="mt-4">
            <div class="flex items-center justify-between mb-1">
                <x-input-label for="password" value="Contrasena" class="uppercase tracking-[0.08em] text-xs text-[#1d2247]" />
                @if (Route::has('password.request'))
                    <a class="text-xs font-semibold text-[#4f5cf0] hover:text-[#3d4adc]" href="{{ route('password.request') }}" wire:navigate>
                        Olvidaste tu contrasena?
                    </a>
                @endif
            </div>

            <x-text-input wire:model="form.password" id="password" class="block mt-1 w-full rounded-xl border-[#d7d6e7] bg-[#ecebf5] focus:border-[#4f5cf0] focus:ring-[#4f5cf0]"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember" class="inline-flex items-center">
                <input wire:model="form.remember" id="remember" type="checkbox" class="rounded border-[#c8c7df] text-[#4f5cf0] shadow-sm focus:ring-[#4f5cf0]" name="remember">
                <span class="ms-2 text-sm text-[#4f536a]">Mantener sesion iniciada</span>
            </label>
        </div>

        <div class="mt-5">
            <x-primary-button class="w-full justify-center py-3 text-base bg-[#4f5cf0] hover:bg-[#3d4adc] focus:bg-[#3d4adc] active:bg-[#3743ca] focus:ring-[#4f5cf0] rounded-xl shadow-[0_10px_22px_rgba(79,92,240,0.35)]">
                Iniciar sesion
            </x-primary-button>
        </div>

        <p class="mt-5 border-t border-[#e4e2ef] pt-4 text-center text-sm text-[#59607a]">
            Aun no eres parte del sistema?
            <a href="{{ route('register.organization') }}" class="font-semibold text-[#4f5cf0] hover:text-[#3d4adc]" wire:navigate>Solicitar acceso</a>
        </p>
    </form>
</div>
