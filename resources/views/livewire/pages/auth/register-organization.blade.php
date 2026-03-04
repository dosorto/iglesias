<?php

use App\Models\Iglesias;
use App\Models\User;
use App\Services\Tenancy\TenantProvisioner;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

new #[Layout('layouts.guest')] class extends Component
{
    public int $step = 1;

    // Datos de la iglesia
    public string $nombre = '';
    public string $direccion = '';
    public string $email_iglesia = '';
    public string $telefono_iglesia = '';

    // Datos del usuario
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function nextStep(): void
    {
        $this->validateStepOne();
        $this->step = 2;
    }

    public function previousStep(): void
    {
        $this->step = 1;
    }

    public function registerOrganization(): void
    {
        $this->validateStepOne();
        $validated = $this->validate([
            'name'     => ['required', 'string', 'min:3', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $provisioner = app(TenantProvisioner::class);

        // 1. Crear registro en BD central
        $iglesia = Iglesias::create([
            'nombre'         => $this->nombre,
            'direccion'      => $this->direccion,
            'parroco_nombre' => $validated['name'], // ← usa el nombre del usuario
            'telefono'       => $this->telefono_iglesia ?: null,
            'email'          => $this->email_iglesia ?: null,
            'estado'         => 'Activa',
        ]);

        // 2. Crear BD tenant y correr migraciones
        $tenant = $provisioner->provisionDatabase($iglesia);

        // 3. Guardar credenciales del tenant en BD central
        $iglesia->update([
            'db_connection' => $tenant['connection'],
            'db_host'       => $tenant['host'],
            'db_port'       => $tenant['port'],
            'db_database'   => $tenant['database'],
            'db_username'   => $tenant['username'],
            'db_password'   => $tenant['password'],
        ]);

        $tenantConnection = $tenant['connection'];
        $previousDefault  = config('database.default');

        Config::set('database.default', $tenantConnection);
        DB::purge($tenantConnection);
        DB::reconnect($tenantConnection);

        try {
            $user = DB::transaction(function () use ($validated, $iglesia, $tenantConnection) {

                // 4. Insertar iglesia en BD tenant
                DB::connection($tenantConnection)->table('iglesias')->insert([
                    'nombre'         => $iglesia->nombre,
                    'direccion'      => $iglesia->direccion,
                    'parroco_nombre' => $iglesia->parroco_nombre,
                    'telefono'       => $iglesia->telefono,
                    'email'          => $iglesia->email,
                    'estado'         => $iglesia->estado,
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ]);

                // 5. Crear rol admin en BD tenant
                $adminRole = Role::firstOrCreate([
                    'name'       => 'admin',
                    'guard_name' => 'web',
                ]);

                // 6. Crear usuario en BD tenant
                $user = User::create([
                    'id_iglesia'        => $iglesia->id,
                    'name'              => $validated['name'],
                    'email'             => strtolower($validated['email']),
                    'email_verified_at' => now(),
                    'password'          => Hash::make($validated['password']),
                ]);

                $user->assignRole($adminRole);

                return $user;
            });

            app(PermissionRegistrar::class)->forgetCachedPermissions();

        } finally {
            Config::set('database.default', $previousDefault);
            DB::purge($previousDefault);
            DB::reconnect($previousDefault);
        }

        session()->put('tenant', [
            'id_iglesia' => $iglesia->id,
            'connection' => $tenantConnection,
            'host'       => $tenant['host'],
            'port'       => $tenant['port'],
            'database'   => $tenant['database'],
            'username'   => $tenant['username'],
            'password'   => $tenant['password'],
        ]);

        event(new Registered($user));
        Auth::login($user);

        $this->redirect(route('register-perfil', absolute: false), navigate: true);
    }

    private function validateStepOne(): void
    {
        $this->validate([
            'nombre'           => ['required', 'string', 'min:3', 'max:200'],
            'direccion'        => ['required', 'string', 'min:5'],
            'email_iglesia'    => ['nullable', 'email', 'max:200'],
            'telefono_iglesia' => ['nullable', 'string', 'max:20'],
        ], [
            'nombre.required'    => 'El nombre de la iglesia es obligatorio.',
            'direccion.required' => 'La ubicación física es necesaria.',
        ]);
    }
}; ?>

<div class="space-y-6">
    <div class="text-center">
        <h1 class="text-2xl font-bold text-gray-900">Crear Cuenta de Iglesia</h1>
        <p class="mt-2 text-sm text-gray-600">Completa 2 pasos para iniciar con tu cuenta administradora.</p>
    </div>

    {{-- Stepper --}}
    <div class="flex items-center gap-3">
        <div class="flex items-center gap-2">
            <span class="inline-flex h-8 w-8 items-center justify-center rounded-full text-sm font-semibold
                {{ $step > 1 ? 'bg-green-500 text-white' : ($step === 1 ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-500') }}">
                {{ $step > 1 ? '✓' : '1' }}
            </span>
            <span class="text-sm {{ $step >= 1 ? 'text-gray-900 font-medium' : 'text-gray-500' }}">Iglesia</span>
        </div>
        <div class="h-px flex-1 bg-gray-200"></div>
        <div class="flex items-center gap-2">
            <span class="inline-flex h-8 w-8 items-center justify-center rounded-full text-sm font-semibold
                {{ $step === 2 ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-500' }}">2</span>
            <span class="text-sm {{ $step >= 2 ? 'text-gray-900 font-medium' : 'text-gray-500' }}">Usuario</span>
        </div>
    </div>

    {{-- PASO 1: Datos de la Iglesia --}}
    @if ($step === 1)
        <form wire:submit="nextStep" class="space-y-4">
            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Datos de la Iglesia</p>

            <div>
                <x-input-label for="nombre" value="Nombre de la Iglesia *" />
                <x-text-input wire:model="nombre" id="nombre" class="mt-1 block w-full" type="text" required autofocus />
                <x-input-error :messages="$errors->get('nombre')" class="mt-1" />
            </div>

            <div>
                <x-input-label for="direccion" value="Dirección *" />
                <x-text-input wire:model="direccion" id="direccion" class="mt-1 block w-full" type="text" required />
                <x-input-error :messages="$errors->get('direccion')" class="mt-1" />
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <x-input-label for="telefono_iglesia" value="Teléfono" />
                    <x-text-input wire:model="telefono_iglesia" id="telefono_iglesia" class="mt-1 block w-full" type="text" />
                    <x-input-error :messages="$errors->get('telefono_iglesia')" class="mt-1" />
                </div>
                <div>
                    <x-input-label for="email_iglesia" value="Correo de la Iglesia" />
                    <x-text-input wire:model="email_iglesia" id="email_iglesia" class="mt-1 block w-full" type="email" />
                    <x-input-error :messages="$errors->get('email_iglesia')" class="mt-1" />
                </div>
            </div>

            <div class="flex justify-end">
                <x-primary-button>Siguiente →</x-primary-button>
            </div>
        </form>
    @endif

    {{-- PASO 2: Datos del Usuario --}}
    @if ($step === 2)
        <form wire:submit="registerOrganization" class="space-y-4">
            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Datos del Usuario Administrador</p>

            {{-- Resumen iglesia --}}
            <div class="rounded-lg border border-gray-200 bg-gray-50 p-3 text-sm text-gray-700 space-y-1">
                <div>Iglesia: <span class="font-semibold">{{ $nombre }}</span></div>
                @if($telefono_iglesia)
                    <div>Teléfono: <span class="font-semibold">{{ $telefono_iglesia }}</span></div>
                @endif
            </div>

            <div>
                <x-input-label for="name" value="Nombre del Usuario *" />
                <x-text-input wire:model="name" id="name" class="mt-1 block w-full" type="text" required autofocus />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="email" value="Correo del Usuario *" />
                <x-text-input wire:model="email" id="email" class="mt-1 block w-full" type="email" required />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="password" value="Contraseña *" />
                <x-text-input wire:model="password" id="password" class="mt-1 block w-full" type="password" required />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="password_confirmation" value="Confirmar Contraseña *" />
                <x-text-input wire:model="password_confirmation" id="password_confirmation" class="mt-1 block w-full" type="password" required />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="flex items-center justify-between">
                <x-secondary-button type="button" wire:click="previousStep">← Volver</x-secondary-button>
                <x-primary-button>
                    <span wire:loading.remove wire:target="registerOrganization">Crear Iglesia →</span>
                    <span wire:loading wire:target="registerOrganization">Creando...</span>
                </x-primary-button>
            </div>
        </form>
    @endif
</div>