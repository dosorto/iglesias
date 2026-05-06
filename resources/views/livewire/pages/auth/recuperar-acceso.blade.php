<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

new #[Layout('layouts.guest')] class extends Component
{
    public int $paso = 1;

    // Paso 1
    public string $email  = '';
    public string $codigo = '';

    // Paso 2
    public string $password              = '';
    public string $password_confirmation = '';

    // ID del usuario verificado (se guarda en estado Livewire server-side)
    public ?int $usuario_id = null;

    public function verificarCodigo(): void
    {
        $this->validate([
            'email'  => ['required', 'email'],
            'codigo' => ['required', 'string'],
        ], [
            'email.required'  => 'El correo electrónico es obligatorio.',
            'email.email'     => 'Ingresa un correo electrónico válido.',
            'codigo.required' => 'El código de recuperación es obligatorio.',
        ]);

        $codigoCorrecto = env('APP_RECOVERY_CODE', '');

        if ($codigoCorrecto === '' || $this->codigo !== $codigoCorrecto) {
            $this->addError('codigo', 'El código de recuperación es incorrecto.');
            return;
        }

        $usuario = User::where('email', strtolower(trim($this->email)))->first();

        if (! $usuario) {
            $this->addError('email', 'No existe una cuenta con ese correo electrónico.');
            return;
        }

        $this->usuario_id = $usuario->id;
        $this->paso       = 2;
        $this->resetErrorBag();
    }

    public function cambiarContrasenia(): void
    {
        $this->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'password.required'  => 'La nueva contraseña es obligatoria.',
            'password.min'       => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        $usuario = User::find($this->usuario_id);

        if (! $usuario) {
            $this->paso       = 1;
            $this->usuario_id = null;
            $this->addError('email', 'La sesión expiró. Vuelve a empezar.');
            return;
        }

        $usuario->update(['password' => Hash::make($this->password)]);

        session()->flash('status', 'Contraseña actualizada correctamente. Ya puedes iniciar sesión.');

        $this->redirect(route('login'), navigate: true);
    }
}; ?>

<div>
    @if ($paso === 1)

        <div class="mb-6 border-b border-[#e4e2ef] pb-4">
            <h1 class="font-serif text-3xl text-[#1a1f46] mb-1">Recuperar acceso</h1>
            <p class="text-sm text-[#59607a] mt-1">
                Ingresa tu correo y el código de recuperación proporcionado por el administrador del sistema.
            </p>
        </div>

        <form wire:submit="verificarCodigo">

            <div>
                <x-input-label for="email" value="Correo electrónico"
                    class="uppercase tracking-[0.08em] text-xs text-[#1d2247]" />
                <x-text-input
                    wire:model="email"
                    id="email"
                    type="email"
                    class="block mt-2 w-full rounded-xl border-[#d7d6e7] bg-[#ecebf5]
                           focus:border-[#4f5cf0] focus:ring-[#4f5cf0]"
                    required
                    autofocus
                    autocomplete="email" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="codigo" value="Código de recuperación"
                    class="uppercase tracking-[0.08em] text-xs text-[#1d2247]" />
                <x-text-input
                    wire:model="codigo"
                    id="codigo"
                    type="text"
                    inputmode="numeric"
                    class="block mt-2 w-full rounded-xl border-[#d7d6e7] bg-[#ecebf5]
                           focus:border-[#4f5cf0] focus:ring-[#4f5cf0] tracking-widest"
                    required
                    autocomplete="off" />
                <x-input-error :messages="$errors->get('codigo')" class="mt-2" />
            </div>

            <div class="mt-5">
                <x-primary-button
                    class="w-full justify-center py-3 text-base bg-[#4f5cf0] hover:bg-[#3d4adc]
                           focus:bg-[#3d4adc] active:bg-[#3743ca] focus:ring-[#4f5cf0]
                           rounded-xl shadow-[0_10px_22px_rgba(79,92,240,0.35)]">
                    <span wire:loading.remove wire:target="verificarCodigo">Verificar código</span>
                    <span wire:loading wire:target="verificarCodigo">Verificando...</span>
                </x-primary-button>
            </div>

        </form>

        <p class="mt-5 border-t border-[#e4e2ef] pt-4 text-center text-sm text-[#59607a]">
            <a href="{{ route('login') }}" class="font-semibold text-[#4f5cf0] hover:text-[#3d4adc]" wire:navigate>
                Volver al inicio de sesión
            </a>
        </p>

    @else

        <div class="mb-6 border-b border-[#e4e2ef] pb-4">
            <h1 class="font-serif text-3xl text-[#1a1f46] mb-1">Nueva contraseña</h1>
            <p class="text-sm text-[#59607a] mt-1">Elige una contraseña segura para tu cuenta.</p>
        </div>

        <form wire:submit="cambiarContrasenia">

            <div>
                <x-input-label for="password" value="Nueva contraseña"
                    class="uppercase tracking-[0.08em] text-xs text-[#1d2247]" />
                <x-text-input
                    wire:model="password"
                    id="password"
                    type="password"
                    class="block mt-2 w-full rounded-xl border-[#d7d6e7] bg-[#ecebf5]
                           focus:border-[#4f5cf0] focus:ring-[#4f5cf0]"
                    required
                    autofocus
                    autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="password_confirmation" value="Confirmar contraseña"
                    class="uppercase tracking-[0.08em] text-xs text-[#1d2247]" />
                <x-text-input
                    wire:model="password_confirmation"
                    id="password_confirmation"
                    type="password"
                    class="block mt-2 w-full rounded-xl border-[#d7d6e7] bg-[#ecebf5]
                           focus:border-[#4f5cf0] focus:ring-[#4f5cf0]"
                    required
                    autocomplete="new-password" />
            </div>

            <div class="mt-5">
                <x-primary-button
                    class="w-full justify-center py-3 text-base bg-[#4f5cf0] hover:bg-[#3d4adc]
                           focus:bg-[#3d4adc] active:bg-[#3743ca] focus:ring-[#4f5cf0]
                           rounded-xl shadow-[0_10px_22px_rgba(79,92,240,0.35)]">
                    <span wire:loading.remove wire:target="cambiarContrasenia">Guardar contraseña</span>
                    <span wire:loading wire:target="cambiarContrasenia">Guardando...</span>
                </x-primary-button>
            </div>

        </form>

        <p class="mt-5 border-t border-[#e4e2ef] pt-4 text-center text-sm text-[#59607a]">
            <button wire:click="$set('paso', 1)" type="button"
                class="font-semibold text-[#4f5cf0] hover:text-[#3d4adc]">
                ← Volver al paso anterior
            </button>
        </p>

    @endif
</div>
