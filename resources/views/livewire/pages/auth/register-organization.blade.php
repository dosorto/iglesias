<?php

use App\Models\Organization;
use App\Models\Iglesias;
use App\Models\User;
use App\Services\Tenancy\TenantProvisioner;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

new #[Layout('layouts.guest')] class extends Component
{
    public int $step = 1;

    public string $nombre = '';
    public string $direccion = '';
    public string $parroco_nombre = '';
    public string $email_iglesia = '';
    public string $telefono_iglesia = '';

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
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $provisioner = app(TenantProvisioner::class);

        // 1. Crear Iglesia en DB Central
        $iglesia = Iglesias::create([
            'nombre'         => $this->nombre,
            'direccion'      => $this->direccion,
            'parroco_nombre' => $this->parroco_nombre,
            'telefono'       => $this->telefono_iglesia ?: null,
            'email'          => $this->email_iglesia ?: null,
            'estado'         => 'Activa',
        ]);

        // 2. Provisionar Base de Datos del Tenant
        $tenant = $provisioner->provisionDatabase($iglesia);
        
        $iglesia->update([
            'db_connection' => $tenant['connection'],
            'db_host'       => $tenant['host'],
            'db_port'       => $tenant['port'],
            'db_database'   => $tenant['database'],
            'db_username'   => $tenant['username'],
            'db_password'   => $tenant['password'],
        ]);

        // 3. Cambio de conexión (Purge y Reconnect tal cual lo tienes)
        $tenantConnection = $tenant['connection'];
        $previousDefault = config('database.default');
        Config::set('database.default', $tenantConnection);

        if ($tenantConnection !== $previousDefault) {
            DB::purge($tenantConnection);
            DB::reconnect($tenantConnection);
        }

        try {
            /** @var User $user */
            $user = DB::transaction(function () use ($validated, $iglesia) {
                $adminRole = Role::firstOrCreate([
                    'name' => 'admin',
                    'guard_name' => 'web',
                ]);

                // Asignamos el ID de la iglesia al usuario
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
            // Regreso a la conexión principal
            Config::set('database.default', $previousDefault);
            if ($tenantConnection !== $previousDefault) {
                DB::purge($previousDefault);
                DB::reconnect($previousDefault);
            }
        }

        // 4. Guardar en sesión (Corregido: $iglesia->id)
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
            'parroco_nombre'   => ['required', 'string', 'min:3', 'max:200'],
            'email_iglesia'    => ['nullable', 'email', 'max:200'],
            'telefono_iglesia' => ['nullable', 'string', 'max:20'],
        ], [
            'nombre.required'         => 'El nombre de la iglesia es obligatorio.',
            'parroco_nombre.required' => 'Debe ingresar el nombre del párroco responsable.',
            'direccion.required'      => 'La ubicación física es necesaria.',
        ]);
    }

    private function generateOrganizationSlug(string $name): string
    {
        $base = Str::slug($name);
        $seed = $base !== '' ? $base : 'organizacion';
        $slug = $seed;
        $counter = 1;

        while (Organization::where('slug', $slug)->exists()) {
            $slug = "{$seed}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}; ?>

<div class="space-y-6">
    <div class="text-center">
        <h1 class="text-2xl font-bold text-gray-900">Crear Cuenta de Iglesia</h1>
        <p class="mt-2 text-sm text-gray-600">Completa 2 pasos para iniciar con tu cuenta administradora.</p>
    </div>

    <div class="flex items-center gap-3">
        <div class="flex items-center gap-2">
            <span class="inline-flex h-8 w-8 items-center justify-center rounded-full {{ $step >= 1 ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-500' }}">1</span>
            <span class="text-sm {{ $step >= 1 ? 'text-gray-900 font-medium' : 'text-gray-500' }}">Iglesia</span>
        </div>
        <div class="h-px flex-1 bg-gray-200"></div>
        <div class="flex items-center gap-2">
            <span class="inline-flex h-8 w-8 items-center justify-center rounded-full {{ $step >= 2 ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-500' }}">2</span>
            <span class="text-sm {{ $step >= 2 ? 'text-gray-900 font-medium' : 'text-gray-500' }}">Usuario</span>
        </div>
    </div>

    @if ($step === 1)
        <form wire:submit="nextStep" class="space-y-4">
            <div>
                <x-input-label for="nombre" value="Nombre de la Iglesia" />
                <x-text-input wire:model="nombre" id="nombre" class="mt-1 block w-full" type="text" required />
                <x-input-error :messages="$errors->get('nombre')" />
            </div>

            <div>
                <x-input-label for="direccion" value="Dirección" />
                <x-text-input wire:model="direccion" id="direccion" class="mt-1 block w-full" type="text" required />
                <x-input-error :messages="$errors->get('direccion')" />
            </div>

            <div>
                <x-input-label for="parroco_nombre" value="Nombre del Párroco" />
                <x-text-input wire:model="parroco_nombre" id="parroco_nombre" class="mt-1 block w-full" type="text" required />
                <x-input-error :messages="$errors->get('parroco_nombre')" />
            </div>

            <x-primary-button>Siguiente</x-primary-button>
        </form>
    @endif

    @if ($step === 2)
        <form wire:submit="registerOrganization" class="space-y-4">
            <div class="rounded-lg border border-gray-200 bg-gray-50 p-3 text-sm text-gray-700">
                Iglesia: <span class="font-semibold">{{ $nombre }}</span>
            </div>

            <div>
                <x-input-label for="name" value="Nombre del Usuario" />
                <x-text-input wire:model="name" id="name" class="mt-1 block w-full" type="text" required autofocus />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="email" value="Correo del Usuario" />
                <x-text-input wire:model="email" id="email" class="mt-1 block w-full" type="email" required />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="password" value="Contraseña" />
                <x-text-input wire:model="password" id="password" class="mt-1 block w-full" type="password" required />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="password_confirmation" value="Confirmar contraseña" />
                <x-text-input wire:model="password_confirmation" id="password_confirmation" class="mt-1 block w-full" type="password" required />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="flex items-center justify-between">
                <x-secondary-button type="button" wire:click="previousStep">Volver</x-secondary-button>
                <x-primary-button>Crear Iglesia e ir a perfil</x-primary-button>
            </div>
        </form>
    @endif
</div>