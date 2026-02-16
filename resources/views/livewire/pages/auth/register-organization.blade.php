<?php

use App\Models\Organization;
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

    public string $organization_name = '';
    public string $organization_email = '';
    public string $organization_phone = '';

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

        $organization = Organization::create([
            'name' => $this->organization_name,
            'slug' => $this->generateOrganizationSlug($this->organization_name),
            'email' => $this->organization_email ?: null,
            'phone' => $this->organization_phone ?: null,
        ]);

        $tenant = $provisioner->provisionDatabase($organization);

        $organization->update([
            'db_connection' => $tenant['connection'],
            'db_host' => $tenant['host'],
            'db_port' => $tenant['port'],
            'db_database' => $tenant['database'],
            'db_username' => $tenant['username'],
            'db_password' => $tenant['password'],
        ]);

        $tenantConnection = $tenant['connection'];
        $previousDefault = config('database.default');
        Config::set('database.default', $tenantConnection);
        if ($tenantConnection !== $previousDefault) {
            DB::purge($tenantConnection);
            DB::reconnect($tenantConnection);
        }

        try {
            /** @var User $user */
            $user = DB::transaction(function () use ($validated) {
                $adminRole = Role::firstOrCreate([
                    'name' => 'admin',
                    'guard_name' => 'web',
                ]);

                $user = User::create([
                    'organization_id' => null,
                    'name' => $validated['name'],
                    'email' => strtolower($validated['email']),
                    'email_verified_at' => now(),
                    'password' => Hash::make($validated['password']),
                ]);

                $user->assignRole($adminRole);

                return $user;
            });

            app(PermissionRegistrar::class)->forgetCachedPermissions();
        } finally {
            Config::set('database.default', $previousDefault);
            if ($tenantConnection !== $previousDefault) {
                DB::purge($previousDefault);
                DB::reconnect($previousDefault);
            }
        }

        session()->put('tenant', [
            'organization_id' => $organization->id,
            'connection' => $tenantConnection,
            'host' => $tenant['host'],
            'port' => $tenant['port'],
            'database' => $tenant['database'],
            'username' => $tenant['username'],
            'password' => $tenant['password'],
        ]);

        event(new Registered($user));
        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }

    private function validateStepOne(): void
    {
        $this->validate([
            'organization_name' => ['required', 'string', 'min:3', 'max:255', 'unique:organizations,name'],
            'organization_email' => ['nullable', 'email', 'max:255'],
            'organization_phone' => ['nullable', 'string', 'max:30'],
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
        <h1 class="text-2xl font-bold text-gray-900">Crear Cuenta de Organización</h1>
        <p class="mt-2 text-sm text-gray-600">Completa 2 pasos para iniciar con tu cuenta administradora.</p>
    </div>

    <div class="flex items-center gap-3">
        <div class="flex items-center gap-2">
            <span class="inline-flex h-8 w-8 items-center justify-center rounded-full {{ $step >= 1 ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-500' }}">1</span>
            <span class="text-sm {{ $step >= 1 ? 'text-gray-900 font-medium' : 'text-gray-500' }}">Organización</span>
        </div>
        <div class="h-px flex-1 bg-gray-200"></div>
        <div class="flex items-center gap-2">
            <span class="inline-flex h-8 w-8 items-center justify-center rounded-full {{ $step >= 2 ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-500' }}">2</span>
            <span class="text-sm {{ $step >= 2 ? 'text-gray-900 font-medium' : 'text-gray-500' }}">Administrador</span>
        </div>
    </div>

    @if ($step === 1)
        <form wire:submit="nextStep" class="space-y-4">
            <div>
                <x-input-label for="organization_name" value="Nombre de la organización" />
                <x-text-input wire:model="organization_name" id="organization_name" class="mt-1 block w-full" type="text" required autofocus />
                <x-input-error :messages="$errors->get('organization_name')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="organization_email" value="Correo de la organización (opcional)" />
                <x-text-input wire:model="organization_email" id="organization_email" class="mt-1 block w-full" type="email" />
                <x-input-error :messages="$errors->get('organization_email')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="organization_phone" value="Teléfono (opcional)" />
                <x-text-input wire:model="organization_phone" id="organization_phone" class="mt-1 block w-full" type="text" />
                <x-input-error :messages="$errors->get('organization_phone')" class="mt-2" />
            </div>

            <div class="flex items-center justify-between">
                <a href="{{ route('login') }}" wire:navigate class="text-sm text-gray-600 underline">Ya tengo cuenta</a>
                <x-primary-button>Siguiente</x-primary-button>
            </div>
        </form>
    @endif

    @if ($step === 2)
        <form wire:submit="registerOrganization" class="space-y-4">
            <div class="rounded-lg border border-gray-200 bg-gray-50 p-3 text-sm text-gray-700">
                Organización: <span class="font-semibold">{{ $organization_name }}</span>
            </div>

            <div>
                <x-input-label for="name" value="Nombre del administrador" />
                <x-text-input wire:model="name" id="name" class="mt-1 block w-full" type="text" required autofocus />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="email" value="Correo del administrador" />
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
                <x-primary-button>Crear organización e ingresar</x-primary-button>
            </div>
        </form>
    @endif
</div>
