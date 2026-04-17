<?php

use App\Models\Iglesias;
use App\Models\Religion;
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
use Livewire\WithFileUploads;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

new #[Layout('layouts.guest')] class extends Component
{
    use WithFileUploads;

    public int $step = 1;

    public string $nombre           = '';
    public string $direccion        = '';
    public string $email_iglesia    = '';
    public string $telefono_iglesia = '';
    public ?int   $id_religion      = null;
    public $path_logo               = null;   // ← nuevo

    public string $name                  = '';
    public string $email                 = '';
    public string $password              = '';
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

        // Guardar logo si fue subido
        $logoPath = null;
        if ($this->path_logo) {
            $logoPath = $this->path_logo->store('logos', 'public');
        }

        $provisioner = app(TenantProvisioner::class);

        // 1. Crear iglesia en BD central
        $iglesia = Iglesias::create([
            'nombre'         => $this->nombre,
            'direccion'      => $this->direccion,
            'parroco_nombre' => $validated['name'],
            'telefono'       => $this->telefono_iglesia ?: null,
            'email'          => $this->email_iglesia    ?: null,
            'estado'         => 'Activa',
            'id_religion'    => $this->id_religion      ?: null,
            'path_logo'      => $logoPath,
        ]);

        // 2. Crear BD tenant
        $tenant = $provisioner->provisionDatabase($iglesia);

        // 3. Guardar credenciales en BD central
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

        $centralConfig = config('database.connections.mysql');
        config(["database.connections.{$tenantConnection}" => array_merge($centralConfig, [
            'database' => $tenant['database'],
        ])]);

        Config::set('database.default', $tenantConnection);
        DB::purge($tenantConnection);
        DB::reconnect($tenantConnection);

        $iglesiaTenantId = null;

        try {
            // 4. Insertar iglesia en BD tenant (con path_logo)
            DB::connection($tenantConnection)->table('iglesias')->insert([
                'nombre'         => $iglesia->nombre,
                'direccion'      => $iglesia->direccion,
                'parroco_nombre' => $iglesia->parroco_nombre,
                'telefono'       => $iglesia->telefono,
                'email'          => $iglesia->email,
                'estado'         => $iglesia->estado,
                'id_religion'    => $iglesia->id_religion,
                'path_logo'      => $iglesia->path_logo,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);

            $iglesiaTenantId = DB::connection($tenantConnection)
                ->table('iglesias')
                ->latest('id')
                ->first()
                ->id;

            $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);

            $user = User::create([
                'id_iglesia'        => $iglesiaTenantId,
                'name'              => $validated['name'],
                'email'             => strtolower($validated['email']),
                'email_verified_at' => now(),
                'password'          => Hash::make($validated['password']),
            ]);

            $user->assignRole($adminRole);
            app(PermissionRegistrar::class)->forgetCachedPermissions();

        } finally {
            Config::set('database.default', $previousDefault);
            DB::purge($previousDefault);
            DB::reconnect($previousDefault);
        }

        session()->put('tenant', [
            'id_iglesia'        => $iglesia->id,
            'id_iglesia_tenant' => $iglesiaTenantId,
            'connection'        => $tenantConnection,
        ]);

        event(new Registered($user));
        Auth::login($user);

        session()->flash('success', 'La iglesia se ha creado correctamente. Ahora completa tu perfil de encargado.');

        $this->redirect(route('register-perfil', absolute: false), navigate: true);
    }

    private function validateStepOne(): void
    {
        $this->validate([
            'nombre'           => ['required', 'string', 'min:3', 'max:200'],
            'direccion'        => ['required', 'string', 'min:5'],
            'email_iglesia'    => ['nullable', 'email', 'max:200'],
            'telefono_iglesia' => ['nullable', 'string', 'max:20'],
            'id_religion'      => ['required', 'exists:religion,id'],
            'path_logo'        => ['nullable', 'image', 'max:2048'],
        ], [
            'nombre.required'      => 'El nombre de la iglesia es obligatorio.',
            'direccion.required'   => 'La ubicación física es necesaria.',
            'id_religion.required' => 'Debes seleccionar una religión.',
            'id_religion.exists'   => 'La religión seleccionada no es válida.',
            'path_logo.image'      => 'El logo debe ser una imagen.',
            'path_logo.max'        => 'El logo no debe superar los 2MB.',
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

    {{-- PASO 1 --}}
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

            {{-- Religión --}}
            <div>
                <x-input-label for="id_religion" value="Religión *" />
                <select wire:model="id_religion" id="id_religion" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm
                               focus:border-indigo-500 focus:ring-indigo-500 text-sm
                               dark:bg-gray-700 dark:border-gray-600 dark:text-white
                               @error('id_religion') border-red-500 @enderror">
                    <option value="">— Selecciona una religión —</option>
                    @foreach (\App\Models\Religion::orderBy('religion')->get() as $rel)
                        <option value="{{ $rel->id }}" {{ $id_religion == $rel->id ? 'selected' : '' }}>
                            {{ $rel->religion }}
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('id_religion')" class="mt-1" />
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

            {{-- Logo de la Iglesia --}}
            <div>
                <x-input-label value="Logo de la Iglesia (opcional)" class="mb-2" />

                <label for="path_logo"
                       class="group relative flex flex-col items-center justify-center w-full
                              rounded-xl border-2 border-dashed cursor-pointer transition-all duration-200
                              {{ $path_logo
                                  ? 'border-green-400 bg-green-50 dark:bg-green-900/10'
                                  : 'border-gray-300 bg-gray-50 hover:border-blue-400 hover:bg-blue-50 dark:bg-gray-700/40 dark:border-gray-600 dark:hover:border-blue-500' }}
                              min-h-[180px] p-6">

                    @if ($path_logo)
                        {{-- Vista previa --}}
                        <div class="flex flex-col items-center gap-3 w-full">
                            <img src="{{ $path_logo->temporaryUrl() }}"
                                 alt="Vista previa logo"
                                 class="max-h-36 w-auto rounded-lg border border-green-200 object-contain shadow-sm" />
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span class="text-sm font-medium text-green-600">Logo cargado correctamente</span>
                            </div>
                            <span class="text-xs text-gray-400 underline group-hover:text-blue-500 transition-colors">
                                Haz clic para cambiar el logo
                            </span>
                        </div>
                    @else
                        {{-- Estado vacío --}}
                        <div class="flex flex-col items-center gap-3 text-center">
                            <div class="w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center
                                        group-hover:bg-blue-100 dark:group-hover:bg-blue-900/30 transition-colors">
                                <svg class="w-8 h-8 text-gray-400 group-hover:text-blue-500 transition-colors"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                          d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-700 dark:text-gray-200 group-hover:text-blue-600 transition-colors">
                                    Haz clic para subir el logo
                                </p>
                                <p class="text-xs text-gray-400 mt-1">
                                    o arrastra y suelta la imagen aquí
                                </p>
                            </div>
                            <div class="flex items-center gap-2 flex-wrap justify-center">
                                <span class="px-2 py-0.5 rounded-full bg-gray-100 dark:bg-gray-700 text-xs text-gray-500">PNG</span>
                                <span class="px-2 py-0.5 rounded-full bg-gray-100 dark:bg-gray-700 text-xs text-gray-500">JPG</span>
                                <span class="px-2 py-0.5 rounded-full bg-gray-100 dark:bg-gray-700 text-xs text-gray-500">JPEG</span>
                                <span class="px-2 py-0.5 rounded-full bg-gray-100 dark:bg-gray-700 text-xs text-gray-500">Máx. 2MB</span>
                            </div>
                        </div>
                    @endif

                    <input
                        wire:model="path_logo"
                        id="path_logo"
                        type="file"
                        accept="image/*"
                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" />
                </label>

                {{-- Loading --}}
                <div wire:loading wire:target="path_logo"
                     class="mt-2 flex items-center gap-2 text-sm text-blue-600">
                    <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                    </svg>
                    Cargando imagen...
                </div>

                <x-input-error :messages="$errors->get('path_logo')" class="mt-1" />
            </div>

            <div class="flex justify-end">
                <x-primary-button>Siguiente →</x-primary-button>
            </div>
        </form>
    @endif

    {{-- PASO 2 --}}
    @if ($step === 2)
        <form wire:submit="registerOrganization" class="space-y-4">
            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Datos del Usuario Administrador</p>

            {{-- Resumen paso 1 con logo --}}
            <div class="rounded-lg border border-gray-200 bg-gray-50 p-3 text-sm text-gray-700 space-y-2">
                @if ($path_logo)
                    <div class="flex items-center gap-3 pb-2 border-b border-gray-200">
                        <img src="{{ $path_logo->temporaryUrl() }}"
                             alt="Logo iglesia"
                             class="w-12 h-12 rounded-lg object-contain border border-gray-200 bg-white" />
                        <span class="font-semibold text-base">{{ $nombre }}</span>
                    </div>
                @else
                    <div>Iglesia: <span class="font-semibold">{{ $nombre }}</span></div>
                @endif
                @if($telefono_iglesia)
                    <div>Teléfono: <span class="font-semibold">{{ $telefono_iglesia }}</span></div>
                @endif
                @if($id_religion)
                    <div>Religión:
                        <span class="font-semibold">
                            {{ \App\Models\Religion::find($id_religion)?->religion ?? '—' }}
                        </span>
                    </div>
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

    {{-- Mensaje de éxito --}}
    @if (session()->has('success'))
        <div class="rounded-md bg-green-50 border border-green-200 p-4 flex items-start gap-3">
            <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-sm text-green-700 font-medium">{{ session('success') }}</p>
        </div>
    @endif
</div>