<?php

use App\Livewire\Forms\LoginForm;
use App\Models\Iglesias;
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

        $pendingEncargado = (bool) Session::get('pending_encargado_registration');
        $postLoginPath = $pendingEncargado ? '/register-perfil' : '/dashboard';

        $tenantSubdomainUrl = Session::pull('tenant_login_subdomain_url');
        if ($tenantSubdomainUrl) {
            $this->redirect(rtrim($tenantSubdomainUrl, '/') . $postLoginPath, navigate: false);

            return;
        }

        $defaultRoute = $pendingEncargado
            ? route('register-perfil', absolute: false)
            : route('dashboard', absolute: false);

        $this->redirectIntended(default: $defaultRoute, navigate: true);
    }
}; ?>

@php($canRegisterOrganization = ! Iglesias::registrationLocked())

<div>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="login">
        <div class="mb-6 border-b border-[#e4e2ef] pb-4">
            <h1 class="mb-2 font-serif text-4xl text-[#1a1f46]">Bienvenido de nuevo</h1>
        </div>

        <div>
            <x-input-label for="email" value="Correo electrónico" class="uppercase tracking-[0.08em] text-xs text-[#1d2247]" />
            <x-text-input wire:model="form.email" id="email" class="mt-2 block w-full rounded-xl border-[#d7d6e7] bg-[#ecebf5] focus:border-[#4f5cf0] focus:ring-[#4f5cf0]" type="email" name="email" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <div class="mb-1 flex items-center justify-between">
                <x-input-label for="password" value="Contraseña" class="uppercase tracking-[0.08em] text-xs text-[#1d2247]" />
                @if (Route::has('recuperar-acceso'))
                    <a class="text-xs font-semibold text-[#4f5cf0] hover:text-[#3d4adc]"
                       href="{{ route('recuperar-acceso') }}" wire:navigate>
                        ¿Olvidaste tu contraseña?
                    </a>
                @endif
            </div>

            <x-text-input wire:model="form.password" id="password" class="mt-1 block w-full rounded-xl border-[#d7d6e7] bg-[#ecebf5] focus:border-[#4f5cf0] focus:ring-[#4f5cf0]"
                          type="password"
                          name="password"
                          required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
        </div>

        <div class="mt-4 block">
            <label for="remember" class="inline-flex items-center">
                <input wire:model="form.remember" id="remember" type="checkbox" class="rounded border-[#c8c7df] text-[#4f5cf0] shadow-sm focus:ring-[#4f5cf0]" name="remember">
                <span class="ms-2 text-sm text-[#4f536a]">Mantener sesión iniciada</span>
            </label>
        </div>

        <div class="mt-5">
            <x-primary-button class="w-full justify-center rounded-xl bg-[#4f5cf0] py-3 text-base shadow-[0_10px_22px_rgba(79,92,240,0.35)] hover:bg-[#3d4adc] focus:bg-[#3d4adc] focus:ring-[#4f5cf0] active:bg-[#3743ca]">
                Iniciar sesión
            </x-primary-button>
        </div>

        @if ($canRegisterOrganization)
            <p class="mt-5 border-t border-[#e4e2ef] pt-4 text-center text-sm text-[#59607a]">
                ¿Aún no eres parte del sistema?
                <a href="{{ route('register.organization') }}" class="font-semibold text-[#4f5cf0] hover:text-[#3d4adc]" wire:navigate>Solicitar acceso</a>
            </p>
        @endif
    </form>
</div>
